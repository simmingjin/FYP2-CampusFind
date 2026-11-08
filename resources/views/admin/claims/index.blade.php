
<x-app-layout>
    <div class="max-w-7xl mx-auto py-10 px-4">

        <h1 class="text-3xl font-bold text-green-700 mb-6">
            Admin Claim Management
        </h1>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="bg-white shadow rounded-xl overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Item</th>
                        <th class="p-3">Claimant</th>
                        <th class="p-3">Reason</th>
                        <th class="p-3">Proof</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Review</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($claims as $claim)
                        <tr class="border-t align-top">
                            <td class="p-3">
                                {{ $claim->foundItem?->item_name ?? 'Item unavailable' }}
                            </td>

                            <td class="p-3">
                                {{ $claim->user?->name ?? 'User unavailable' }}
                                <div class="text-sm text-gray-500">
                                    {{ $claim->user?->email }}
                                </div>
                            </td>

                            <td class="p-3">
                                {{ $claim->claim_reason }}
                            </td>

                            <td class="p-3">
                                @if($claim->proof_image)
                                    <a
                                        href="{{ route('admin.claims.proof', $claim) }}"
                                        target="_blank"
                                        rel="noopener"
                                        class="text-blue-600 underline">
                                        View Proof
                                    </a>
                                @else
                                    No proof uploaded
                                @endif
                            </td>

                            <td class="p-3">
                                {{ ucfirst($claim->status) }}
                                @if($claim->admin_remark)
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $claim->admin_remark }}
                                    </p>
                                @endif
                            </td>

                            <td class="p-3 min-w-64">
                                @if($claim->status === 'pending')

                                    <form
                                        action="{{ route('admin.claims.approve', $claim) }}"
                                        method="POST"
                                        class="mb-3">
                                        @csrf
                                        @method('PATCH')

                                        <textarea
                                            name="admin_remark"
                                            rows="2"
                                            placeholder="Optional approval remark"
                                            class="w-full border rounded p-2 mb-2"></textarea>

                                        <button
                                            type="submit"
                                            onclick="return confirm('Approve this claim?')"
                                            class="bg-green-600 text-white px-3 py-2 rounded">
                                            Approve
                                        </button>
                                    </form>

                                    <form
                                        action="{{ route('admin.claims.reject', $claim) }}"
                                        method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <textarea
                                            name="admin_remark"
                                            rows="2"
                                            required
                                            maxlength="2000"
                                            placeholder="Reason for rejection"
                                            class="w-full border rounded p-2 mb-2"></textarea>

                                        <button
                                            type="submit"
                                            onclick="return confirm('Reject this claim?')"
                                            class="bg-red-600 text-white px-3 py-2 rounded">
                                            Reject
                                        </button>
                                    </form>

                                @else
                                    <span class="text-gray-500">
                                        Reviewed
                                    </span>

                                    @if($claim->reviewer)
                                        <p class="text-sm text-gray-500 mt-1">
                                            By {{ $claim->reviewer->name }}
                                        </p>
                                    @endif

                                    @if($claim->reviewed_at)
                                        <p class="text-sm text-gray-500">
                                            {{ $claim->reviewed_at->format('d M Y, h:i A') }}
                                        </p>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center">
                                No claim requests found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $claims->links() }}
        </div>

    </div>
</x-app-layout>
