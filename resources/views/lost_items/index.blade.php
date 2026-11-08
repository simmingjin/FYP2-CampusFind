<!DOCTYPE html>
<html>
<head>
    <title>Lost Items</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-green-50 p-10">

<h1 class="text-3xl font-bold mb-6">
    Lost Items
</h1>
<form method="GET" class="mb-5">

    <input
        type="text"
        name="search"
        placeholder="Search Item"
        class="border p-2 rounded">

    <select
        name="category"
        class="border p-2 rounded">

        <option value="">
            All Categories
        </option>

        <option value="Electronics">
            Electronics
        </option>

        <option value="Books">
            Books
        </option>

        <option value="Wallet">
            Wallet
        </option>

        <option value="Student Card">
            Student Card
        </option>

    </select>

    <select
        name="location"
        class="border p-2 rounded">

        <option value="">
            All Locations
        </option>

        <option value="Library">
            Library
        </option>

        <option value="Main Building">
            Main Building
        </option>

        <option value="Computer Lab">
            Computer Lab
        </option>

        <option value="Cafeteria">
            Cafeteria
        </option>

    </select>

    <button
        class="bg-green-600 text-white px-4 py-2 rounded">

        Search

    </button>

</form>

<a href="{{ route('lost-items.create') }}"
   class="bg-green-600 text-white px-4 py-2 rounded">

    Report Lost Item

</a>

<hr class="my-6">

@foreach($items as $item)

<div class="bg-white shadow p-4 rounded mb-4">

    <h2 class="font-bold text-xl">
        {{ $item->item_name }}
    </h2>

    <p>{{ $item->category }}</p>

    <p>{{ $item->location }}</p>

    <p>{{ $item->lost_date }}</p>

    @if($item->image)

    <img src="{{ asset('storage/' . $item->image) }}"
         width="200">

@endif

<a
    href="{{ route('lost-items.ai-matches', $item->id) }}"
    class="inline-block bg-purple-600 text-white px-3 py-2 rounded mb-3">
    Find Possible Matches (AI)
</a>

<div class="mt-4 flex gap-2">

    <a href="{{ route('lost-items.edit', $item->id) }}"
       class="bg-blue-500 text-white px-3 py-2 rounded">

        Edit

    </a>

    <form action="{{ route('lost-items.destroy', $item->id) }}"
          method="POST">

        @csrf
        @method('DELETE')

        <button
            type="submit"
            onclick="return confirm('Are you sure you want to cancel this report? This action cannot be undone.')"
            class="bg-red-500 text-white px-3 py-2 rounded">

            Cancel Report

        </button>

    </form>

</div>
@endforeach

</body>
</html>