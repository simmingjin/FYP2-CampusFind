<!DOCTYPE html>
<html>
<head>
    <title>Report Lost Item</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-green-50 p-10">

<h1 class="text-3xl font-bold mb-6">
    Report Lost Item
</h1>

 <form action="{{ route('lost-items.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    <input type="text"
           name="item_name"
           placeholder="Item Name"
           class="border p-2 w-full mb-3"
           required>

    <div class="mb-4">

        <label class="block font-semibold mb-2">
            Category
        </label>

        <select name="category"
                class="border p-2 w-full rounded"
                required>

            <option value="">-- Select Category --</option>
            <option value="Electronics">Electronics</option>
            <option value="Books">Books</option>
            <option value="Stationery">Stationery</option>
            <option value="Clothing">Clothing</option>
            <option value="Wallet">Wallet</option>
            <option value="Keys">Keys</option>
            <option value="Student Card">Student Card</option>
            <option value="Others">Others</option>

        </select>

    </div>

    <textarea name="description"
              placeholder="Description"
              class="border p-2 w-full mb-3"
              required></textarea>

    <div class="mb-4">

        <label class="block font-semibold mb-2">
            Location
        </label>

        <select name="location"
                class="border p-2 w-full rounded"
                required>

            <option value="">-- Select Location --</option>

            <option value="Library">Library</option>
            <option value="Main Building">Main Building</option>
            <option value="IEB Classroom">IEB Classroom</option>
            <option value="Computer Lab">Computer Lab</option>
            <option value="Lecture Hall">Lecture Hall</option>
            <option value="Cafeteria">Cafeteria</option>
            <option value="Parking Area">Parking Area</option>

        </select>

    </div>

    <div class="mb-4">

        <label class="block font-semibold mb-2">
            Lost Item Date
        </label>

        <input type="date"
               name="lost_date"
               max="{{ date('Y-m-d') }}"
               class="border p-2 w-full rounded"
               required>

    </div>

    <div class="mb-4">

        <label class="block font-semibold mb-2">
            Lost Item Image
        </label>

        <input type="file"
               name="image"
               accept="image/*"
               capture="environment"
               class="border p-2 w-full">

    </div>

    <button type="submit"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">

        Save Lost Item

    </button>

</form>

</body>
</html>