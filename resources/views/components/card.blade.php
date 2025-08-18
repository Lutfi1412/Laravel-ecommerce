<div
    class="w-full py-2 max-w-sm bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
    <img class="p-8 rounded-t-lg h-48 w-96 object-contain" src="{{ $image }}" alt="product image" />

    <div class="px-5 pb-5">
        <!-- Judul -->
        <h5 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white">{{ $title }}</h5>

        <!-- Deskripsi -->
        @if (!empty($description))
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                {{ $description }}
            </p>
        @endif

        @if (!empty($stock))
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Tersisa {{ $stock }}
            </p>
        @endif

        <!-- Harga -->
        <div class="mt-3">
            <span class="text-3xl font-bold text-gray-900 dark:text-white">
                Rp{{ number_format($price, 0, ',', '.') }}
            </span>
        </div>

        <!-- Tombol Add to Cart -->
        @if ($showButton)
            <button
                onclick="openModal('{{ $title }}', '{{ $description }}', '{{ $price }}', '{{ $stock }}', '{{ $image }}', '{{ $id }}')"
                class="block mt-4 w-full text-center text-white bg-blue-700 hover:bg-blue-800 focus:ring-4
           focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5
           dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Add to cart
            </button>
        @endif

        @if (request()->routeIs('dashboard.customers.home'))
            <input type="hidden" name="id" value="{{ $id }}">
        @endif
    </div>
</div>

{{-- MODAL SUDAH DIHAPUS DARI SINI - SEKARANG ADA DI DASHBOARD --}}
