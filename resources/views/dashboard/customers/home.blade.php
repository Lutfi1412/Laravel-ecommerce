@extends('layouts.dashboard')
@section('content')
    <h1 class="text-2xl font-bold mb-4">Dashboard Customer</h1>
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
    </div>

    <div class="container mx-auto px-4 py-8">
        @if ($products && count($products) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <x-card image="{{ url('database/image/' . $product->image) }}" stock="{{ $product->stock }}"
                        title="{{ $product->name }}" description="{{ $product->description }}" :price="$product->price"
                        :show-button="true" :id="$product->id" />
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

    <div id="modal-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div id="modal-content" class="bg-white rounded-2xl shadow-2xl max-w-md w-full relative">
                    <div class="p-6">
                        <!-- Close Button -->
                        <button onclick="closeModal()"
                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200 z-10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </button>

                        <!-- Modal Header -->
                        <div class="pr-8 mb-6">
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">🛒 Add to Cart</h2>
                            <p class="text-gray-600">Configure your order details</p>
                        </div>

                        <!-- Product Image -->
                        <div class="mb-6">
                            <img id="modal-image" src="" alt=""
                                class="w-full h-48 object-cover rounded-xl shadow-md">
                        </div>

                        <!-- Product Info -->
                        <div class="mb-6">
                            <h3 id="modal-title" class="text-xl font-semibold text-gray-800 mb-3"></h3>

                            <!-- Product Description -->
                            <div class="mb-4">
                                <p id="modal-description" class="text-gray-600 text-sm leading-relaxed"></p>
                            </div>

                            <div class="flex justify-between items-center mb-4">
                                <span id="modal-price" class="text-3xl font-bold text-blue-600"></span>
                                <div class="text-right">
                                    <span class="text-sm text-gray-500">Available Stock</span>
                                    <div id="modal-stock" class="text-lg font-semibold text-green-600"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Quantity Selector -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                            <div class="relative flex items-center max-w-[8rem]">
                                <button onclick="decreaseQuantity()" type="button"
                                    class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-s-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none">
                                    <svg class="w-3 h-3 text-gray-900" fill="none" viewBox="0 0 18 2">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M1 1h16" />
                                    </svg>
                                </button>
                                <input type="number" id="quantity-input"
                                    class="bg-gray-50 border-x-0 border-gray-300 h-11 text-center text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block w-full py-2.5"
                                    value="1" min="1" required />
                                <button onclick="increaseQuantity()" type="button"
                                    class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-e-lg p-3 h-11 focus:ring-gray-100 focus:ring-2 focus:outline-none">
                                    <svg class="w-3 h-3 text-gray-900" fill="none" viewBox="0 0 18 18">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M9 1v16M1 9h16" />
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Maximum quantity based on available stock</p>
                        </div>

                        <input type="hidden" id="product-id">

                        <!-- Total Price -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-medium text-gray-700">Total Price:</span>
                                <span id="total-price" class="text-2xl font-bold text-green-600"></span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <button onclick="buyNow()"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span>Buy Now</span>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

<script>
    let currentProduct = {};
    let currentQuantity = 1;

    function openModal(title, description, price, stock, image, productId) {
        currentProduct = {
            title,
            description,
            price,
            stock,
            image,
            id: productId
        };
        currentQuantity = 1;

        // Update modal content
        document.getElementById('modal-title').textContent = title;
        document.getElementById('modal-description').textContent = description;
        document.getElementById('modal-price').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(price);
        document.getElementById('modal-stock').textContent = stock + ' units';
        document.getElementById('modal-image').src = image;
        document.getElementById('quantity-input').value = currentQuantity;
        document.getElementById('quantity-input').max = stock;
        document.getElementById('product-id').value = productId;

        updateTotalPrice();
        document.getElementById('modal-backdrop').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('modal-backdrop').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function increaseQuantity() {
        const input = document.getElementById('quantity-input');
        const maxQuantity = parseInt(input.max);
        if (currentQuantity < maxQuantity) {
            currentQuantity++;
            input.value = currentQuantity;
            updateTotalPrice();
        }
    }

    function decreaseQuantity() {
        if (currentQuantity > 1) {
            currentQuantity--;
            document.getElementById('quantity-input').value = currentQuantity;
            updateTotalPrice();
        }
    }

    function updateTotalPrice() {
        const price = parseFloat(currentProduct.price);
        const total = price * currentQuantity;
        document.getElementById('total-price').textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(total);
    }

    function buyNow() {
        const userId = "{{ session('user_id') }}";
        const productId = document.getElementById('product-id').value;
        const jumlah = parseInt(document.getElementById('quantity-input').value) || 1;
        const status = 0;

        const totalPriceElement = document.getElementById('total-price');

        // Debug: Check if element exists
        if (!totalPriceElement) {
            alert('Element with ID "total-price" not found');
            return;
        }

        // Get text content instead of value (since it's a span, not input)
        const totalPriceText = totalPriceElement.textContent || '';

        const totalPrice = parseInt(totalPriceText.replace(/[^\d]/g, '')) || 0;
        console.log("Total price number:", totalPrice);

        // Validate inputs before making request

        fetch('/dashboard/customer/get-harga', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    total_price: totalPrice // Already converted to number above
                })
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                return res.json();
            })
            .then(data => {
                if (data.error) {
                    console.error('Server error:', data.error);
                    alert('Payment initialization failed: ' + data.message);
                    return;
                }

                snap.pay(data.snapToken, {
                    onSuccess: async function(result) {
                        try {
                            const response = await fetch('/dashboard/customer/add-transaksi', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    customer_id: userId, // Fixed: use userId instead of undefined customer_id
                                    barang_id: productId, // Fixed: use productId instead of undefined barang_id
                                    jumlah: jumlah,
                                    status: 1, // Update status to completed (1) since payment successful
                                    payment_result: result,
                                    order_id: data
                                        .order_id // Include order_id from payment token response
                                })
                            });
                            location.reload();

                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }

                            const transactionData = await response.json();
                            console.log("Transaction saved successfully:", transactionData);

                        } catch (err) {
                            console.error("Error saving transaction:", err);
                        }
                    },
                    onPending: function(result) {
                        console.log("Payment pending: ", result);
                        // Optionally save transaction with pending status
                    },
                    onError: function(result) {
                        console.log("Payment failed: ", result);
                        alert('Payment failed. Please try again.');
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to initialize payment. Please try again.');
            });
    }
    document.addEventListener('DOMContentLoaded', function() {
        // Handle quantity input changes
        const quantityInput = document.getElementById('quantity-input');
        if (quantityInput) {
            quantityInput.addEventListener('input', function() {
                const value = parseInt(this.value);
                const max = parseInt(this.max);

                if (value >= 1 && value <= max) {
                    currentQuantity = value;
                    updateTotalPrice();
                } else if (value > max) {
                    this.value = max;
                    currentQuantity = max;
                    updateTotalPrice();
                } else if (value < 1) {
                    this.value = 1;
                    currentQuantity = 1;
                    updateTotalPrice();
                }
            });
        }

        // Close modal when clicking outside
        const modalBackdrop = document.getElementById('modal-backdrop');
        if (modalBackdrop) {
            modalBackdrop.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modalBackdrop && !modalBackdrop.classList.contains('hidden')) {
                closeModal();
            }
        });
    });
</script>
