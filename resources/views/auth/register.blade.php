@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">

            {{-- Alert Sukses --}}
            @if (session('success'))
                <div id="success-alert" class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                    {{ session('success') }}
                </div>

                <script>
                    setTimeout(function() {
                        window.location.href = "{{ route('login') }}";
                    }, 1000); // 2 detik
                </script>
            @endif

            {{-- Alert Error Validasi --}}
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full p-2 border rounded focus:ring focus:ring-blue-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium">Role</label>
                    <select name="role" class="w-full p-2 border rounded focus:ring focus:ring-blue-300" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="seller" {{ old('role') == 'seller' ? 'selected' : '' }}>Seller</option>
                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                </div>
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
                <div>
                    <label class="block text-sm font-medium">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full p-2 border rounded focus:ring focus:ring-blue-300" required>
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                    Daftar
                </button>
            </form>
            <p class="text-sm text-center mt-4">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login</a>
            </p>
        </div>
    </div>
@endsection
