<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Show - Order') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <a title="back" href="{{ route('orders.index') }}"
                    class="inline-flex items-center px-4 py-2 mb-4 text-xs font-semibold tracking-widest text-black uppercase transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-gray disabled:opacity-25">
                    Go back
                </a>                
                    <div class="mb-4">
                        <label for="order_code" class="block mb-2 text-sm font-bold text-gray-700 inline-flex">Order Code :</label>
                        <span>{{ $order->order_code }}</span>
                    </div>
                    @if ($order->products->isNotEmpty())
                    <div class="mb-4">                        
                        @foreach ($order->products as $product)
                         <br>
                        <label for="order_code" class="block mb-2 text-sm font-bold text-gray-700 inline-flex">Products :</label>
                        <span>{{ $product->name }}</span>    <br>
                        @if ($product->category->isNotEmpty())   
                        <span><b>Parent Category :: </b></span>
                            @foreach ($product->category as $parentCategory)
                            {{ $parentCategory->name }}
                            @if ($parentCategory->subcategories->isNotEmpty())   
                                @foreach ($parentCategory->subcategories as $subCategory)
                                @if ($parentCategory->pivot->category_id == $parentCategory->id && $parentCategory->pivot->sub_category_id == $subCategory->id)
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <span><b>Sub Category :: </b> {{ $subCategory->name }}</span>   
                                @endif 
                                @endforeach                                
                            @endif
                            @endforeach
                            
                        @endif
                        @endforeach                        
                    </div>
                    @endif

                    <div class="mb-4">
                        <label for="total_amount" class="block mb-2 text-sm font-bold text-gray-700 inline-flex">Total Amount :</label>
                        <span>{{ $order->total_amount }}</span>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
