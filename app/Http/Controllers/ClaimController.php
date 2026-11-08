<?php

namespace App\Http\Controllers;


use App\Models\Claim;
use App\Models\FoundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ClaimController extends Controller
{
    /**
     * Student submits a claim for a found item.
     */
    public function store(Request $request, FoundItem $foundItem)
    {
        $data = $request->validate([
            'claim_reason' => 'required|string|max:2000',
            'proof_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($foundItem->user_id === $request->user()->id) {
            return back()->withErrors([
                'claim' => 'You cannot claim your own found item.',
            ]);
        }

        if ($foundItem->status !== 'available') {
            return back()->withErrors([
                'claim' => 'This item is no longer available for claiming.',
            ]);
        }

        $existingClaim = Claim::where('found_item_id', $foundItem->id)
            ->where('user_id', $request->user()->id)
            ->where('status', 'pending')
            ->exists();

        if ($existingClaim) {
            return back()->withErrors([
                'claim' => 'You already have a pending claim for this item.',
            ]);
        }

        // Keep ownership evidence on the private local disk.
        $proofPath = null;

        if ($request->hasFile('proof_image')) {
            $proofPath = $request->file('proof_image')
                ->store('claim_proofs', 'local');
        }

        Claim::create([
            'found_item_id' => $foundItem->id,
            'user_id' => $request->user()->id,
            'claim_reason' => $data['claim_reason'],
            'proof_image' => $proofPath,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('my-claims.index')
            ->with('success', 'Your claim has been submitted.');
    }

    /**
     * Student sees their own claims only.
     */
    public function myClaims(Request $request)
    {
        $claims = Claim::with('foundItem')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('claims.my-index', compact('claims'));
    }

    /**
     * Administrator sees all claims.
     */
    public function adminIndex()
    {
        $claims = Claim::with([
            'foundItem',
            'user',
            'reviewer',
        ])
            ->latest()
            ->paginate(15);

        return view('admin.claims.index', compact('claims'));
    }

    /**
     * Administrator approves a pending claim.
     */
    public function approve(Request $request, Claim $claim)
    {
        $data = $request->validate([
            'admin_remark' => 'nullable|string|max:2000',
        ]);

        DB::transaction(function () use ($claim, $data, $request) {
            $lockedClaim = Claim::whereKey($claim->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedClaim->status !== 'pending') {
                throw ValidationException::withMessages([
                    'claim' => 'This claim has already been reviewed.',
                ]);
            }

            $item = FoundItem::whereKey($lockedClaim->found_item_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($item->status !== 'available') {
                throw ValidationException::withMessages([
                    'claim' => 'This item is no longer available.',
                ]);
            }

            $lockedClaim->update([
                'status' => 'approved',
                'admin_remark' => $data['admin_remark'] ?? null,
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
            ]);

            $item->update([
                'status' => 'claim_approved',
            ]);

            // Only one claim can be approved for this item.
            Claim::where('found_item_id', $item->id)
                ->where('id', '!=', $lockedClaim->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'admin_remark' => 'Another claim was approved.',
                    'reviewed_by' => $request->user()->id,
                    'reviewed_at' => now(),
                ]);
        });

        return back()->with(
            'success',
            'Claim approved successfully.'
        );
    }

    /**
     * Administrator rejects a pending claim.
     */
    public function reject(Request $request, Claim $claim)
    {
        $data = $request->validate([
            'admin_remark' => 'required|string|max:2000',
        ]);

        DB::transaction(function () use ($claim, $data, $request) {
            $lockedClaim = Claim::whereKey($claim->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedClaim->status !== 'pending') {
                throw ValidationException::withMessages([
                    'claim' => 'This claim has already been reviewed.',
                ]);
            }

            $lockedClaim->update([
                'status' => 'rejected',
                'admin_remark' => $data['admin_remark'],
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
            ]);
        });

        return back()->with(
            'success',
            'Claim rejected successfully.'
        );
    }

    /**
     * Serve private ownership evidence to an administrator.
     */
    public function proof(Claim $claim)
    {
        abort_unless($claim->proof_image, 404);

        abort_unless(
            Storage::disk('local')->exists($claim->proof_image),
            404
        );

        return Storage::disk('local')->response(
            $claim->proof_image
        );
    }
}
