<x-app-layout>
    <!-- Header Section Starts -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Show - Product Details') }}
        </h2>
    </x-slot>
    <!-- Header Section Ends -->

    <!-- Show Div Section Starts -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <a title="back" href="{{ route('products.index') }}"
                    class="inline-flex items-center px-4 py-2 mb-4 text-xs font-semibold tracking-widest text-black uppercase transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-gray disabled:opacity-25">
                    Go back
                </a>
                <div class="mb-4">
                    <label for="name"
                        class="block mb-2 text-sm font-bold text-gray-700 inline-flex font-semibold">Product Name :
                    </label>
                    <span>{{ $product->name }}</span>
                </div>

                <div class="mb-4">
                    <label for="description"
                        class="block mb-2 text-sm font-bold text-gray-700 inline-flex font-semibold">{{ __('Description') }}
                        :
                    </label>
                    <span>{{ $product->description }}</span>
                </div>

                <div class="mb-4 flex">
                    <label for="image" class="block mb-2 text-sm font-bold text-gray-700 font-semibold">Image
                        :</label>
                    @if ($product->getProductImagesHasMany->isNotEmpty())
                        @foreach ($product->getProductImagesHasMany as $prodImg)
                            <img src="{{ asset('storage/products/' . $prodImg->filename) }}" heigth="150"
                                width="150" />&nbsp;&nbsp;&nbsp;
                        @endforeach
                    @else
                        <span>No Images Uploaded...!</span>
                    @endif
                </div>

                <div class="mb-4">
                    @if ($product->category->isNotEmpty())
                        @php $isParentCatExist = []; @endphp
                        @foreach ($product->category as $parentCat)
                            @if(!in_array($parentCat->name,$isParentCatExist))
                            <span><b>Parent Category :: </b></span>
                            {{ $parentCat->name }} <br>
                            @endif
                            @php
                                $isParentCatExist[] = $parentCat->name;
                            @endphp
                            @if ($parentCat->subcategories->isNotEmpty())
                                @foreach ($parentCat->subcategories as $subCat)
                                    @if ($parentCat->pivot->category_id == $parentCat->id && $parentCat->pivot->sub_category_id == $subCat->id)
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        <span><b>Sub Category :: </b></span><i> {{ $subCat->name }} </i> <br>
                                    @endif
                                @endforeach <!-- Sub Category Loop Ends -->
                            @endif
                        @endforeach <!-- Parent Category Loop Ends -->
                    @endif
                </div>

                <div class="mb-4">
                    <label for="price"
                        class="block mb-2 text-sm font-bold text-gray-700 inline-flex font-semibold">Price : </label>
                    <span>{{ $product->price }}</span>
                </div>

                <div class="mb-4">
                    <label for="qty"
                        class="block mb-2 text-sm font-bold text-gray-700 inline-flex font-semibold">Quantity : </label>
                    <span>{{ $product->qty }}</span>
                </div>
                <div class="mb-4">
                    <label for="status"
                        class="block mb-2 text-sm font-bold text-gray-700"><b>{{ __('Status') }} : </b><span>{{ ($product->status)?'Active':'In-Active' }}</span> </label>
                 </div>
            </div>
        </div>
    </div>
    <!-- Show Div Section Ends -->

</x-app-layout>
