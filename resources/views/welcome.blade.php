@vite(['resources/css/app.css', 'resources/js/app.js'])
<!DOCTYPE html>
<html>
<head>
    <title>CampusFind</title>

</head>

</head>

<body class="bg-green-50">

   <!-- Navbar -->
<nav class="bg-green-700 p-4">

    <div class="flex justify-between items-center max-w-6xl mx-auto">

        <h1 class="text-white text-2xl font-bold">
            CampusFind
        </h1>

        <div class="flex items-center gap-3">

            <a href="http://127.0.0.1:8000/"
                 class="text-white px-6 py-3 rounded-lg hover:bg-green-600"
            >
                Home
            </a>

            <a href="/login"
                 class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600"
            >
                Login
            </a>

            <a href="/register"
               class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600"
            >
                Register
            </a>
        </div>

    </div>

</nav>

   <!-- Hero Section -->
<div class="text-center py-24">

    <h1 class="text-6xl font-bold text-green-700">
        CampusFind
    </h1>

    <p class="mt-4 text-xl text-gray-600">
        Keep Track of Lost & Found Items on Campus
    </p>

    <p class="mt-2 text-gray-500">
        Report • Search • Claim
    </p>

    <div class="mt-8">
        <a href="/login"
           class="inline-block bg-green-600 text-white px-8 py-4 rounded-lg">
            Get Started
        </a>
    </div>

    <!-- Statistics -->
    <div class="max-w-4xl mx-auto grid grid-cols-2 gap-6">

      <div class="bg-white shadow p-6 rounded-xl text-center">

    <h2 class="text-green-700 text-xl font-bold">
        Total Lost Items
    </h2>

   <p class="text-5xl mt-4">
        {{ $lostCount }}
     </p>

</div>

        <div class="bg-white shadow p-6 rounded-xl text-center">

    <h2 class="text-green-700 text-xl font-bold">
        Total Found Items
    </h2>

    <p class="text-5xl mt-4">
        {{ $foundCount }}
     </p>

</div>

    </div>

</body>
</html>