<x-app-layout>

    <div class="py-10">

        <div class="max-w-7xl mx-auto">

            <h1 class="text-4xl font-bold text-green-700 mb-8">
                Welcome to CampusFind
            </h1>

            <div class="grid md:grid-cols-2 gap-6">

                <div class="bg-white rounded-xl shadow p-6">

                    <h2 class="text-xl font-bold text-green-700">
                        Lost Items
                    </h2>

                    <p class="text-5xl mt-4">
                      {{ $lostCount }}
                      </p>
                    <button
                        class="mt-4 bg-green-600 text-white px-4 py-2 rounded">
                  <a href="{{ route('lost-items.create') }}">

                      Report Lost Item

                </button>
                 </a>
                 <button
                        class="mt-4 bg-green-600 text-white px-4 py-2 rounded">
                  <a href="{{ route('lost-items.index') }}">

                     View Lost Items

                </button>
                 </a>

                </div>

                <div class="bg-white rounded-xl shadow p-6">

                    <h2 class="text-xl font-bold text-green-700">
                        Found Items
                    </h2>

                   <p class="text-5xl mt-4">
                     {{ $foundCount }}
                  </p>

                    <button
                     class="mt-4 bg-green-600 text-white px-4 py-2 rounded">
                       <a href="{{ route('found-items.create') }}">

                     Report Found Item
                   </button>
                   </a>

                  <button
                        class="mt-4 bg-green-600 text-white px-4 py-2 rounded">
                  <a href="{{ route('found-items.index') }}">

                     View Found Items

                </button>
                 </a>
                </div>

            </div>

        </div>

    </div>
    <div class="mt-10">

    <h2 class="text-2xl font-bold text-white mb-5">
        Recent Lost Items (Latest 5)
    </h2>

    @forelse($lostItems as $item)

    <div class="bg-white p-4 rounded-lg shadow mb-3">

        @if($item->image)

            <img src="{{ Storage::url($item->image) }}"
                 class="w-32 h-32 object-cover rounded mb-3">

        @endif

        <h3 class="font-bold text-lg">
            {{ $item->item_name }}
        </h3>

        <p>
            Category:
            {{ $item->category }}
        </p>

        <p>
            Location:
            {{ $item->location }}
        </p>

        <p>
            Lost Date:
            {{ $item->lost_date }}
        </p>

    </div>

    @empty

    <div class="bg-white p-4 rounded-lg">
        No Lost Item Report Yet
    </div>

    @endforelse

</div>
</div>

</x-app-layout>