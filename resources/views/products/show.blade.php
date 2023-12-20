<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Show - Product Details') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <a title="back" href="{{ route('products.index') }}"
                    class="inline-flex items-center px-4 py-2 mb-4 text-xs font-semibold tracking-widest text-black uppercase transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-gray disabled:opacity-25">
                    Go back
                </a>
                <div class="mb-4">
                    <label for="name" class="block mb-2 text-sm font-bold text-gray-700 inline-flex">Product Name :
                    </label>
                    <span>{{ $product->name }}</span>
                </div>

                <div class="mb-4">
                    <label for="description" class="block mb-2 text-sm font-bold text-gray-700 inline-flex">{{ __('Description') }} :
                    </label>
                    <span>{{ $product->description }}</span>
                </div>

                <div class="mb-4">
                    <label for="image" class="block mb-2 text-sm font-bold text-gray-700">Image "</label>
                    <img src="{{ asset('storage/products/' . $product->image) }}" heigth="150" width="150" />
                </div>

                <div class="mb-4">
                    <label for="parentcategory_name"
                        class="block mb-2 text-sm font-bold text-gray-700 inline-flex">{{ __('Parent category') }} : </label>
                     <span>{{ $product->getParentCatHasOne->name }}</span>
                </div>

                <div class="mb-4">
                    <label for="price" class="block mb-2 text-sm font-bold text-gray-700 inline-flex">Price </label>
                    <span>{{ $product->price }}</span>                    
                </div>

                <div class="mb-4">
                    <label for="qty" class="block mb-2 text-sm font-bold text-gray-700 inline-flex">Quantity </label>
                    <span>{{ $product->qty }}</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>