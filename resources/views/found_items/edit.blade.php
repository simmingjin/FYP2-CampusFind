<!DOCTYPE html>
<html>
<head>
    <title>Edit Found Item</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-green-50 p-10">

    <h1 class="text-3xl font-bold mb-6">
        Edit Found Item
    </h1>

    <form action="{{ route('found-items.update', $foundItem->id) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="mb-4">
            <label>Item Name</label>

            <input type="text"
                   name="item_name"
                   value="{{ $foundItem->item_name }}"
                   class="border p-2 w-full">
        </div>

        <div class="mb-4">
            <label>Category</label>

            <input type="text"
                   name="category"
                   value="{{ $foundItem->category }}"
                   class="border p-2 w-full">
        </div>

        <div class="mb-4">
            <label>Description</label>

            <textarea name="description"
                      class="border p-2 w-full">{{ $foundItem->description }}</textarea>
        </div>

        <div class="mb-4">
            <label>Location</label>

            <input type="text"
                   name="location"
                   value="{{ $foundItem->location }}"
                   class="border p-2 w-full">
        </div>

<div class="mb-4">

    <label>Storage Location</label>

    <select name="storage_location"
            class="border p-2 w-full">

        <option value="Security Office"
            {{ $foundItem->storage_location == 'Security Office' ? 'selected' : '' }}>
            Security Office
        </option>

        <option value="Library Counter"
            {{ $foundItem->storage_location == 'Library Counter' ? 'selected' : '' }}>
            Library Counter
        </option>

        <option value="Student Affairs Office"
            {{ $foundItem->storage_location == 'Student Affairs Office' ? 'selected' : '' }}>
            Student Affairs Office
        </option>

    </select>

</div>
        <div class="mb-4">
            <label>Found Date</label>

            <input type="date"
                   name="found_date"
                   value="{{ $foundItem->found_date }}"
                   class="border p-2 w-full">
        </div>

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded">

            Update Item

        </button>

    </form>

</body>
</html>