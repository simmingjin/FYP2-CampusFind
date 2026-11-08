<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use App\Models\FoundItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class AiMatchingController extends Controller
{
    public function show(LostItem $lostItem)
    {
        $matches = collect();
        $error = null;

        // Check whether the lost item has an accessible image.
        if (
            !$lostItem->image ||
            !Storage::disk('public')->exists($lostItem->image)
        ) {
            $error = 'The lost item image is missing or unavailable.';

            return view(
                'lost_items.ai_matches',
                compact('lostItem', 'matches', 'error')
            );
        }

        // Get up to 10 found items with existing images.
        $foundItems = FoundItem::whereNotNull('image')
            ->latest()
            ->take(50)
            ->get()
            ->filter(function ($item) {
                return Storage::disk('public')
                    ->exists($item->image);
            })
            ->take(10)
            ->values();

        if ($foundItems->isEmpty()) {
            $error = 'No found items with accessible images are available.';

            return view(
                'lost_items.ai_matches',
                compact('lostItem', 'matches', 'error')
            );
        }

        try {
            // Prepare the lost-item image.
            $request = Http::timeout(180)->attach(
                'lost_image',
                Storage::disk('public')->get($lostItem->image),
                basename($lostItem->image)
            );

            // Attach each candidate found-item image.
            foreach ($foundItems as $item) {
                $request = $request->attach(
                    'found_images',
                    Storage::disk('public')->get($item->image),
                    basename($item->image)
                );
            }

            // Send images to the local Python AI service.
            $response = $request->post(
                'http://127.0.0.1:5001/match'
            );

            if (!$response->successful()) {
                Log::warning(
                    'CampusFind AI service returned an error.',
                    ['status' => $response->status()]
                );

                $error = 'The AI matching service returned an error. Please try again.';
            } else {
                $results = $response->json('results', []);

                // Python returns candidate IDs as 1, 2, 3...
                // Map those IDs back to the corresponding database records.
              
            
            $matches = collect($results)
           ->map(function ($result) use ($foundItems, $lostItem) {
           $index = (int) ($result['id'] ?? 0) - 1;

        if (
            !isset($foundItems[$index]) ||
            !isset($result['similarity_percent'])
        ) {
            return null;
        }

        $foundItem = $foundItems[$index];

        $lostCategory = mb_strtolower(
            trim((string) $lostItem->category)
        );

        $foundCategory = mb_strtolower(
            trim((string) $foundItem->category)
        );

        return [
            'item' => $foundItem,
            'similarity_percent' => (float) $result['similarity_percent'],
            'same_category' => $lostCategory !== ''
                && $lostCategory === $foundCategory,
           ];
       })
    ->filter()
    ->sort(function ($a, $b) {
        // Same-category items come first.
        if ($a['same_category'] !== $b['same_category']) {
            return $a['same_category'] ? -1 : 1;
        }

        // Within the same category group, higher similarity comes first.
        return $b['similarity_percent']
            <=> $a['similarity_percent'];
    })
    
    ->values();

      }
        } catch (\Throwable $exception) {
            Log::error(
                'CampusFind AI matching request failed.',
                ['message' => $exception->getMessage()]
            );

            $error = 'Unable to connect to the AI service. Please make sure the Python service is running.';
        }

        return view(
            'lost_items.ai_matches',
            compact('lostItem', 'matches', 'error')
        );
    }
}
