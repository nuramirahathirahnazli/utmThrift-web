
@extends('shared.layouts.login')

@section('title', 'Admin Login')

@section('content')
<div class="max-w-md mx-auto mt-20 bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-semibold mb-6 text-center">Admin Login</h2>
    
    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-2 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label>Email</label>
            <input type="email" name="email" required class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label>Password</label>
            <input type="password" name="password" required class="w-full border px-3 py-2 rounded">
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Login
        </button>
    </form>
</div>
@endsection
