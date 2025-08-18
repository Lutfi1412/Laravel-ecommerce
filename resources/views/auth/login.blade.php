{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full p-2 border rounded focus:ring focus:ring-blue-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Password</label>
                    <input type="password" name="password" class="w-full p-2 border rounded focus:ring focus:ring-blue-300"
                        required>
                </div>

                <div class="flex gap-2">
                    <button type="submit" name="role" value="customer"
                        class="w-1/2 bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                        Login Customer
                    </button>
                    <button type="submit" name="role" value="seller"
                        class="w-1/2 bg-green-500 text-white p-2 rounded hover:bg-green-600">
                        Login Seller
                    </button>
                </div>
            </form>

            <p class="text-sm text-center mt-4">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Daftar</a>
            </p>
        </div>
    </div>
@endsection
