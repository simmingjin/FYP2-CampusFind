<!DOCTYPE html>
<html>
<head>
    <title>Edit Lost Item</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-green-50 p-10">

    <h1 class="text-3xl font-bold mb-6">
        Edit Lost Item
    </h1>

    <form action="{{ route('lost-items.update', $lostItem->id) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="mb-4">
            <label>Item Name</label>

            <input type="text"
                   name="item_name"
                   value="{{ $lostItem->item_name }}"
                   class="border p-2 w-full">
        </div>

        <div class="mb-4">
            <label>Category</label>

            <input type="text"
                   name="category"
                   value="{{ $lostItem->category }}"
                   class="border p-2 w-full">
        </div>

        <div class="mb-4">
            <label>Description</label>

            <textarea name="description"
                      class="border p-2 w-full">{{ $lostItem->description }}</textarea>
        </div>

        <div class="mb-4">
            <label>Location</label>

            <input type="text"
                   name="location"
                   value="{{ $lostItem->location }}"
                   class="border p-2 w-full">
        </div>

        <div class="mb-4">
            <label>Lost Date</label>

            <input type="date"
                   name="lost_date"
                   value="{{ $lostItem->lost_date }}"
                   class="border p-2 w-full">
        </div>

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded">

            Update Item

        </button>

    </form>

</body>
</html>