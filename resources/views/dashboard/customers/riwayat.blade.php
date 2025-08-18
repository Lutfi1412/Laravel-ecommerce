@extends('layouts.dashboard')

{{-- @section('content')
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Dashboard Overview</h1>
        <p class="text-gray-600">Selamat datang di dashboard riwayat</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <span class="text-2xl">📦</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-900">1,234</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <span class="text-2xl">💰</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Penjualan Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900">Rp 5,67M</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <span class="text-2xl">🧾</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-900">89</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <span class="text-2xl">👥</span>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total User</p>
                    <p class="text-2xl font-bold text-gray-900">456</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Aktivitas Terbaru</h2>
        <div class="space-y-4">
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-2 h-2 bg-blue-500 rounded-full mr-4"></div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Produk baru ditambahkan</p>
                    <p class="text-xs text-gray-500">2 menit yang lalu</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-4"></div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Transaksi berhasil</p>
                    <p class="text-xs text-gray-500">5 menit yang lalu</p>
                </div>
            </div>
            <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-4"></div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">User baru mendaftar</p>
                    <p class="text-xs text-gray-500">10 menit yang lalu</p>
                </div>
            </div>
        </div>
    </div>
@endsection --}}

@section('content')
    <x-table>
        <x-slot:head>
            <tr>
                <th class="px-6 py-3">Nama Produk</th>
                <th class="px-6 py-3 hidden sm:table-cell">Harga</th>
                <th class="px-6 py-3 text-center hidden sm:table-cell">Jumlah</th>
                <th class="px-6 py-3">Total</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3 text-center">Status</th>
            </tr>
        </x-slot:head>

        @foreach ($products as $p)
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50">
                <td class="px-6 py-4">{{ $p->nama_produk }}</td>
                <td class="px-6 py-4 hidden sm:table-cell">Rp{{ number_format($p->harga, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-center hidden sm:table-cell">{{ $p->jumlah }}</td>
                <td class="px-6 py-4">Rp{{ number_format($p->total, 0, ',', '.') }}</td>
                <td class="px-6 py-4">{{ $p->created_at->format('H:i d-m-Y') }}</td>
                <td class="px-6 py-4 text-center">
                    @if ($p->status == 1)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                            Sedang Diproses
                        </span>
                    @elseif($p->status == 2)
                        <button
                            class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-full transition-colors"
                            onclick="CustomerUpdateStatus({{ $p->id }}, {{ $p->total }}, {{ $p->barang_id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </button>
                    @elseif($p->status == 3)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                            Pesanan Telah Selesai
                        </span>
                    @endif
                </td>
            </tr>
        @endforeach
    </x-table>
@endsection
