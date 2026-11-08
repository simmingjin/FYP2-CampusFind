<x-guest-layout>

<div class="min-h-screen bg-blue-100 flex items-center justify-center">

<div class="bg-white p-8 rounded shadow-lg w-96">

<h1 class="text-3xl text-center font-bold mb-5">
CampusFind Register
</h1>

<form method="POST" action="{{ route('register') }}">
@csrf

<input
type="text"
name="name"
placeholder="Full Name"
class="w-full border p-2 mb-3">

<input
type="email"
name="email"
placeholder="Student Email"
class="w-full border p-2 mb-3">

<input
type="password"
name="password"
placeholder="Password"
class="w-full border p-2 mb-3">

<input
type="password"
name="password_confirmation"
placeholder="Confirm Password"
class="w-full border p-2 mb-3">

<button
class="bg-green-600 text-white w-full p-2 rounded">
Register
</button>

</form>

</div>

</div>

</x-guest-layout>