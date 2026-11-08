<x-guest-layout>

    <div class="min-h-screen bg-blue-100 flex items-center justify-center">

        <div class="bg-white p-8 rounded-lg shadow-lg w-96">

            <h1 class="text-3xl font-bold text-center mb-2">
                CampusFind
            </h1>

            <p class="text-center text-gray-500 mb-6">
                Lost & Found Management System
            </p>

           <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        class="w-full border rounded p-2"
                        required>
                </div>

                <div class="mb-4">
                    <label>Password</label>

                    <input
                        type="password"
                        name="password"
                        class="w-full border rounded p-2"
                        required>
                </div>

                <button
                    type="submit"
                    class="bg-blue-600 text-white w-full p-2 rounded">

                    Login

                </button>

            </form>

        </div>

    </div>

</x-guest-layout>