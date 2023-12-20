<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit - Order') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <a title="back" href="{{ route('orders.index') }}"
                    class="inline-flex items-center px-4 py-2 mb-4 text-xs font-semibold tracking-widest text-black uppercase transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-gray disabled:opacity-25">
                    Go back
                </a>
                <!-- Calls when validation errors triggers starts -->
                @if ($errors->any())
                    <div class="alert alert-danger rounded-b text-red-600 px-4 py-3 shadow-md my-3" role="alert">
                        <p><strong>Opps Something went wrong</strong></p>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- Calls when validation errors triggers ends -->

                <!-- Calls when session error triggers starts -->
                @if (session('error'))
                    <div class="alert alert-danger rounded-b text-red-600 px-4 py-3 shadow-md my-3" role="alert">
                        <div class="flex">
                            <div>
                                <p class="text-sm text-danger">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                <!-- Calls when session error triggers ends -->

                <form action="{{ route('orders.update', $order->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="order_code" class="block mb-2 text-sm font-bold text-gray-700">Order Code <span
                                class="text-red-600">*</span></label>
                        <input type="text"
                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            name="order_code" placeholder="Enter Order Code"
                            value="{{ old('order_code', $order->order_code) }}" maxlength="10" required>
                        @error('order_code')
                            <span class="text-red-600">{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="products" class="block mb-2 text-sm font-bold text-gray-700">Select Products <span
                                class="text-red-600">*</span></label>
                        <select name="products[]" id="products[]"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            multiple required>
                            <option disabled readonly>Choose a Products</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    @if (old('products') && in_array($product->id, old('products'))) selected 
                                @elseif(!old('products') && in_array($product->id, $selectedProducts))
                                selected @endif>
                                    {{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error('products')
                            <span class="text-red-600">{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="total_amount" class="block mb-2 text-sm font-bold text-gray-700 inline-flex">Total
                            Amount :</label>
                        <span>{{ $order->total_amount }}</span>
                    </div>

                    <div>
                        <button title="update" type="submit"
                            class="inline-flex items-center px-4 py-2 my-3 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
