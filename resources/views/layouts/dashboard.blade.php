<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard - Seller</title>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        .animate-scale-in {
            animation: scaleIn 0.3s ease-out;
        }
    </style>
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>

<body class="bg-gray-100 text-gray-800" x-data="{ sidebarOpen: false }">
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 p-4 flex justify-between items-center shadow-sm fixed w-full z-30">
        <div class="flex items-center space-x-4">
            <button @click="sidebarOpen = !sidebarOpen" class="focus:outline-none">
                <svg class="w-6 h-6 text-gray-600 transition-transform duration-200" fill="none"
                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path x-show="!sidebarOpen" d="M4 6h16M4 12h16M4 18h16" />
                    <path x-show="sidebarOpen" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <div class="text-xl font-bold text-blue-600">Hallo, {{ session('name') }}</div>
        </div>

        @if (@session('role') === 'seller')
            <div class="flex items-center space-x-4">
                <span class="font-medium">Saldo: Rp{{ number_format($saldo, 0, ',', '.') }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">Logout</button>
                </form>
            </div>
        @else
            <div class="flex items-center space-x-4">
                <span class="font-medium"></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">Logout</button>
                </form>
            </div>
        @endif

    </nav>

    <!-- Backdrop untuk mobile -->
    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="sidebarOpen = false"
        class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden" style="display: none;">
    </div>

    <div class="flex pt-16">
        <aside x-show="sidebarOpen" x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="w-64 bg-white h-screen border-r border-gray-200 p-6 fixed z-20 shadow-lg md:shadow-none"
            style="display: none;">
            <h2 class="text-lg font-semibold mb-6 text-gray-800">Menu</h2>
            <ul class="space-y-4">
                @if (session('role') === 'customer')
                    <li>
                        <a href="{{ route('dashboard.customers.home') }}"
                            class="block text-gray-700 hover:text-blue-600">🏠Home</a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('dashboard.sellers.etalase') }}"
                            class="block text-gray-700 hover:text-blue-600">📦Etalase</a>
                    </li>
                @endif
                @if (session('role') === 'seller')
                    <li>
                        <a href="{{ route('dashboard.sellers.data') }}"
                            class="block text-gray-700 hover:text-blue-600">📊 Data Penjualan</a>
                    </li>
                @else
                    <li>
                        <a href="{{ route('dashboard.customers.riwayat') }}"
                            class="block text-gray-700 hover:text-blue-600">
                            🛒 Riwayat Pembelian
                        </a>

                    </li>
                @endif
                @if (session('role') === 'seller')
                    <a href="{{ route('dashboard.sellers.riwayat') }}" class="block text-gray-700 hover:text-blue-600">
                        🛒 Riwayat Pembelian
                    </a>
                @endif
            </ul>
        </aside>
        <main :class="sidebarOpen ? 'md:ml-64' : ''" class="p-8 transition-all duration-300 w-full">
            @yield('content')
        </main>
    </div>
</body>

</html>
