
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Image Matching - CampusFind</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-green-50 p-6 md:p-10">

    <div class="max-w-5xl mx-auto">

        <h1 class="text-3xl font-bold mb-3">
            AI Image Matching
        </h1>

        <p class="text-gray-600 mb-6">
            Potential matches for your lost item, ranked by visual similarity.
        </p>

        <div class="bg-white shadow rounded-lg p-5 mb-6">
            <h2 class="text-xl font-bold mb-3">
                Lost Item
            </h2>

            <p>
                <strong>Item:</strong>
                {{ $lostItem->item_name }}
            </p>

            <p>
                <strong>Category:</strong>
                {{ $lostItem->category }}
            </p>

            <p>
                <strong>Location:</strong>
                {{ $lostItem->location }}
            </p>

            @if($lostItem->image)
                <img
                    src="{{ asset('storage/' . $lostItem->image) }}"
                    alt="{{ $lostItem->item_name }}"
                    class="mt-4 rounded max-h-64 object-contain">
            @endif
        </div>

        @if($error)
            <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
                {{ $error }}
            </div>
        @endif

        @if($matches->isNotEmpty())

            <h2 class="text-2xl font-bold mb-4">
                Possible Found Items
            </h2>
            
          <div class="bg-white shadow rounded-lg p-4 mb-5">
          <label for="matchFilter" class="block font-semibold mb-2">
           Filter AI Matches
         </label>

    <select
        id="matchFilter"
        class="w-full md:w-80 border border-gray-300 rounded-lg px-4 py-2"
    >
        <option value="all">All Matches</option>
        <option value="same_category">Same Category</option>
        <option value="high">High Similarity (80%–100%)</option>
        <option value="medium">Medium Similarity (50%–79.99%)</option>
        <option value="low">Low Similarity (Below 50%)</option>
     </select>

    <p id="matchCount" class="text-gray-600 text-sm mt-3">
        Showing all matches
    </p>
   </div>

     <div
        id="filterEmptyMessage"
        class="hidden bg-yellow-100 text-yellow-800 p-4 rounded-lg mb-5"
>
        No items match this filter. Please try another option.
     </div>


            @foreach($matches as $match)

                @php
                    $item = $match['item'];
                    $score = $match['similarity_percent'];
                @endphp

                
               <div class="ai-match-card bg-white shadow rounded-lg p-5 mb-5"
               data-same-category="{{ ($match['same_category'] ?? false) ? 'yes' : 'no' }}"
                data-similarity="{{ $score }}">
                   
                <div class="flex flex-col md:flex-row gap-5">

                        @if($item->image)
                            <div class="md:w-1/3">
                                <img
                                    src="{{ asset('storage/' . $item->image) }}"
                                    alt="{{ $item->item_name }}"
                                    class="rounded w-full max-h-64 object-contain">
                            </div>
                        @endif

                        <div class="flex-1">

                       
                  <div class="flex flex-wrap items-center gap-2 mb-3">
                  <h3 class="text-xl font-bold">
                   {{ $item->item_name }}
                  </h3>

                 {{-- Category badge --}}
                  @if($match['same_category'] ?? false)
             <span class="bg-green-100 text-green-800 text-xs
              font-bold px-3 py-1 rounded-full">
            Same Category
        </span>
        @else
        <span class="bg-gray-100 text-gray-700 text-xs 
        font-semibold px-3 py-1 rounded-full">
            Different Category
        </span>
       @endif

        {{-- Similarity badge --}}
        @if($score >= 80)
        <span class="bg-green-100 text-green-800 text-xs 
        font-bold px-3 py-1 rounded-full">
            High Similarity
        </span>
         @elseif($score >= 50)
        <span class="bg-yellow-100 text-yellow-800 text-xs 
        font-bold px-3 py-1 rounded-full">
            Medium Similarity
        </span>
        @else
        <span class="bg-red-100 text-red-800 text-xs 
        font-bold px-3 py-1 rounded-full">
            Low Similarity
        </span>
        @endif
      </div>


                            <p>
                                <strong>Category:</strong>
                                {{ $item->category }}
                            </p>

                            <p>
                                <strong>Location:</strong>
                                {{ $item->location }}
                            </p>

                            <p>
                                <strong>Storage Location:</strong>
                                {{ $item->storage_location ?? 'Not specified' }}
                            </p>

                            <p>
                                <strong>Found Date:</strong>
                                {{ $item->found_date ?? 'Not specified' }}
                            </p>

                            <div class="mt-4">
                                <p class="font-semibold mb-2">
                                    Visual Similarity
                                </p>

                                <div class="w-full bg-gray-200 rounded-full h-4">
                                    <div
                                        class="bg-green-600 h-4 rounded-full"
                                        style="width: {{ max(0, min(100, $score)) }}%">
                                    </div>
                                </div>

                                <p class="mt-2 font-bold text-green-700">
                                    {{ number_format($score, 2) }}%
                                </p>
                            </div>

                            <p class="text-sm text-gray-500 mt-3">
                                This score measures visual similarity only.
                                It does not prove ownership.
                            </p>

                            <a
                                href="{{ route('found-items.index') }}"
                                class="inline-block mt-4 bg-green-600 text-white px-4 py-2 rounded">
                                View Found Items
                            </a>

                        </div>
                    </div>
                </div>

            @endforeach

       
            @elseif(!$error)
            <div class="bg-yellow-100 text-yellow-800 p-5 rounded-lg mb-6">
            <h3 class="font-bold text-lg mb-2">
            No High-Similarity Matches Found
        </h3>
        <p>
            No found items reached the minimum visual similarity
            threshold of 50%.
        </p>
        <p class="mt-2">
            You can still browse all found items manually because
            visually different images may belong to the same item.
        </p>
        <a href="{{ route('found-items.index') }}"
           class="inline-block mt-4 bg-green-600 text-white px-4 py-2 rounded">
            Browse All Found Items
        </a>
    </div>
     @endif
 
        <a
            href="{{ route('lost-items.index') }}"
            class="inline-block bg-gray-600 text-white px-4 py-2 rounded">
            Back to Lost Items
        </a>

    </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filter = document.getElementById('matchFilter');
    const cards = document.querySelectorAll('.ai-match-card');
    const count = document.getElementById('matchCount');
    const emptyMessage = document.getElementById('filterEmptyMessage');

    if (!filter || !count || !emptyMessage) {
        return;
    }

    function applyFilter() {
        const selected = filter.value;
        let visibleCount = 0;

        cards.forEach(function (card) {
            const score = Number(card.dataset.similarity);
            const sameCategory =
                card.dataset.sameCategory === 'yes';

            let show = false;

            switch (selected) {
                case 'same_category':
                    show = sameCategory;
                    break;

                case 'high':
                    show = score >= 80;
                    break;

                case 'medium':
                    show = score >= 50 && score < 80;
                    break;

                case 'low':
                    show = score < 50;
                    break;

                default:
                    show = true;
            }

            card.classList.toggle('hidden', !show);

            if (show) {
                visibleCount++;
            }
        });

        count.textContent =
            'Showing ' + visibleCount + ' of ' +
            cards.length + ' matches';

        emptyMessage.classList.toggle(
            'hidden',
            visibleCount !== 0
        );
    }

    filter.addEventListener('change', applyFilter);

    applyFilter();
});
</script>
</body>
</html>
