@extends('layouts.dashboard')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Dashboard Seller</h1>
    <div class="flex w-full gap-2 py-5">
        <!-- Search Input -->
        <div class="relative flex-1">
            <input type="text" id="search-box"
                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500
                   focus:border-blue-500 pl-9 p-2.5"
                placeholder="Cari..." required>
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <!-- Search Icon -->
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-4.35-4.35M9.5 17a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z"></path>
                </svg>
            </div>
        </div>

        <!-- Add Product Button -->
        <button type="button" onclick="AddProduct()"
            class="inline-flex items-center py-2.5 px-4 text-sm font-medium text-white bg-green-600
               rounded-lg border border-green-600 hover:bg-green-700 focus:ring-4
               focus:outline-none focus:ring-green-300">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2 9m10-9l2 9M9 21a1 1 0 1 0 0-2
                                                                                                                                                           1 1 0 0 0 0 2zm8 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z">
                </path>
            </svg>
            Add Product
        </button>
    </div>

    <div class="container mx-auto px-4 py-8">
        @if ($products && count($products) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <x-card image="{{ url('database/image/' . $product->image) }}" title="{{ $product->name }}"
                        description="{{ $product->description }}" stock="{{ $product->stock }}" :price="$product->price"
                        :show-button="false" />
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-500 dark:text-gray-400">
                    <svg class="mx-auto h-12 w-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7L8 19l-4-4m5 5v0a7.07 7.07 0 007 7h0a7.07 7.07 0 007-7v0a7.07 7.07 0 00-7-7h0a7.07 7.07 0 00-7 7v0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No products found</h3>
                    <p class="text-gray-500 dark:text-gray-400">You haven't added any products yet.</p>
                </div>
            </div>
        @endif
    </div>
@endsection
