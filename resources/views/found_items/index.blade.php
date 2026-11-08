

<!DOCTYPE html>
<html>
<head>
    <title>Found Items</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-green-50 p-10">

<h1 class="text-3xl font-bold mb-6">
    Found Items
</h1>

{{-- Display success message --}}
@if(session('success'))
    <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

{{-- Display validation errors --}}
@if($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

{{-- Search form --}}
<form method="GET" class="mb-5">

    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search Item"
        class="border p-2 rounded">

    <select name="category" class="border p-2 rounded">
        <option value="">All Categories</option>

        <option value="Electronics"
            @selected(request('category') === 'Electronics')>
            Electronics
        </option>

        <option value="Books"
            @selected(request('category') === 'Books')>
            Books
        </option>

        <option value="Wallet"
            @selected(request('category') === 'Wallet')>
            Wallet
        </option>

        <option value="Student Card"
            @selected(request('category') === 'Student Card')>
            Student Card
        </option>
    </select>

    <select name="location" class="border p-2 rounded">
        <option value="">All Locations</option>

        <option value="Library"
            @selected(request('location') === 'Library')>
            Library
        </option>

        <option value="Main Building"
            @selected(request('location') === 'Main Building')>
            Main Building
        </option>

        <option value="Computer Lab"
            @selected(request('location') === 'Computer Lab')>
            Computer Lab
        </option>

        <option value="Cafeteria"
            @selected(request('location') === 'Cafeteria')>
            Cafeteria
        </option>
    </select>

    <button
        type="submit"
        class="bg-green-600 text-white px-4 py-2 rounded">
        Search
    </button>

</form>

<a href="{{ route('found-items.create') }}"
   class="inline-block bg-green-600 text-white px-4 py-2 rounded">
    Report Found Item
</a>

<hr class="my-6">

{{-- Display found items --}}
@forelse($items as $item)

    <div class="bg-white shadow p-4 rounded mb-4">

        <h2 class="font-bold text-xl">
            {{ $item->item_name }}
        </h2>

        <p>Category: {{ $item->category }}</p>

        <p>Location: {{ $item->location }}</p>

        <p>
            Storage Location: {{ $item->storage_location }}
        </p>

        <p>Found Date: {{ $item->found_date }}</p>

        <p>
            Status:
            {{ ucfirst(str_replace('_', ' ', $item->status ?? 'available')) }}
        </p>

        {{-- Existing item image --}}
        @if($item->image)
            <img
                src="{{ asset('storage/' . $item->image) }}"
                alt="{{ $item->item_name }}"
                width="200"
                class="mt-3 rounded">
        @endif

        {{-- Existing Edit and Delete buttons --}}
        <div class="mt-4 flex gap-2">

            <a href="{{ route('found-items.edit', $item->id) }}"
               class="bg-blue-500 text-white px-3 py-2 rounded">
                Edit
            </a>

            <form
                action="{{ route('found-items.destroy', $item->id) }}"
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

        {{-- Claim This Item form --}}
        @auth
            @if(
                auth()->id() !== $item->user_id
                && ($item->status ?? 'available') === 'available'
            )

                <form
                    action="{{ route('claims.store', $item) }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="mt-4 border-t pt-4">

                    @csrf

                    <label class="block font-semibold mb-2">
                        Why do you believe this item belongs to you?
                    </label>

                    <textarea
                        name="claim_reason"
                        required
                        maxlength="2000"
                        rows="3"
                        class="w-full border rounded p-2 mb-3"
                        placeholder="Describe identifying details or ownership evidence"></textarea>

                    <label class="block font-semibold mb-2">
                        Upload ownership proof (optional)
                    </label>

                    <input
                        type="file"
                        name="proof_image"
                        accept=".jpg,.jpeg,.png,.webp"
                        class="block mb-3">

                    <p class="text-sm text-gray-500 mb-3">
                        Maximum file size: 2 MB.
                    </p>

                    <button
                        type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded">
                        Claim This Item
                    </button>

                </form>

            @endif
        @endauth

    </div>

@empty

    <div class="bg-white shadow p-6 rounded">
        No found items available.
    </div>

@endforelse

</body>
</html>
