
<x-app-layout>
    <div class="max-w-6xl mx-auto py-10 px-4">

        <h1 class="text-3xl font-bold text-green-700 mb-6">
            My Claims
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

        @forelse($claims as $claim)
            <div class="bg-white shadow rounded-xl p-5 mb-4">

                <h2 class="text-xl font-bold">
                    {{ $claim->foundItem?->item_name ?? 'Item unavailable' }}
                </h2>

                <p class="mt-2">
                    Claim Date:
                    {{ $claim->created_at->format('d M Y, h:i A') }}
                </p>

                <p class="mt-2">
                    Reason: {{ $claim->claim_reason }}
                </p>

                <p class="mt-2">
                    Status:
                    <span class="font-semibold">
                        {{ ucfirst($claim->status) }}
                    </span>
                </p>

                @if($claim->admin_remark)
                    <p class="mt-2">
                        Admin Remark: {{ $claim->admin_remark }}
                    </p>
                @endif

                @if($claim->foundItem)
                    <p class="mt-2">
                        Item Location: {{ $claim->foundItem->location }}
                    </p>
                @endif

            </div>
        @empty
            <div class="bg-white shadow rounded-xl p-6">
                You have not submitted any claims yet.
            </div>
        @endforelse

        <div class="mt-6">
            {{ $claims->links() }}
        </div>

    </div>
</x-app-layout>
