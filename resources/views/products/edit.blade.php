<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit - Product') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <a title="back" href="{{ route('products.index') }}"
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
                <form action="{{ route('products.update', $product->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Product Name <span
                                class="text-red-600">*</span></label>
                        <input type="text"
                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            name="name" placeholder="Enter Product name" value="{{ old('name', $product->name) }}"
                            required>
                        @error('name')
                            <span class="text-red-600 text-danger">{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description"
                            class="block mb-2 text-sm font-bold text-gray-700">{{ __('Description') }} </label>
                        <textarea class="form-control" cols="40" rows="7" name="description"
                            placeholder="{{ __('Enter Product description') }}">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label for="images" class="block mb-2 text-sm font-bold text-gray-700">Product Images</label>
                        <input type="file"
                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            name="images[]" accept=".jpg, .png, .jpeg, .gif" multiple>
                        @if ($product->getProductImagesHasMany->isNotEmpty())
                            <div class="flex mt-4">
                                <input type="hidden" name="img_delete" id="img_delete" value="">
                                @foreach ($product->getProductImagesHasMany as $imgVal)
                                    <div class="img_{{ $imgVal->id }}">
                                        <button type="button" class="btn btn-outline-danger btn-sm img_close"
                                            data="{{ $imgVal->id }}" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            <img class="mx-6"
                                                src="{{ asset('storage/products/' . $imgVal->filename) }}"
                                                heigth="75" width="75" />
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @error('images')
                            <span class="text-red-600 text-danger">{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <!-- KEY :: DYNAMICMULTIROW Starts -->
                    <table id="dynamicAddRemoveTbl"
                        class="w-full table-fixed display cell-border row-border stripe mb-4 mt-4">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border" colspan="3">
                                    <label for="add-row" class="block mb-2 text-sm font-bold text-gray-700"><a
                                            id="add-row"
                                            class="inline-flex items-center px-4 py-2 mx-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-500 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 btn btn-sm btn-info">Add
                                            New</a></label>
                                </th>
                            </tr>
                            <tr>
                                <th class="px-4 py-2 border">Category</th>
                                <th class="px-4 py-2 border">Sub Category</th>
                                <th class="px-4 py-2 border">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- This Row is Use for Clone Rows and Ignored to display on initialize page -->
                            <tr id="row-template" class="bg-gray-100" style="display:none;">
                                <td class="px-4 py-2 border">
                                    <div class="mb-4">
                                        <label for="parentcategory_name"
                                            class="block mb-2 text-sm font-bold text-gray-700">{{ __('Parent category') }}
                                            <span class="text-red-600">*</span></label>
                                        <select class="form-select select_parent_cat" name="select_parent_cat[]"
                                            class="select_parent_cat">
                                            <option selected readonly disabled>
                                                {{ __('Select Parent category') . '--' }}</option>
                                            @foreach ($parent_category as $parent_cat)
                                                <option value="{{ $parent_cat->id }}"
                                                    {{ old('select_parent_cat') == $parent_cat->id ? 'selected' : '' }}>
                                                    {{ $parent_cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </td>
                                <td class="px-4 py-2 border">
                                    <div class="mb-4">
                                        <label for="subcategory_name"
                                            class="block mb-2 text-sm font-bold text-gray-700">{{ __('Sub category') }}
                                            <span class="text-red-600">*</span></label>
                                        <select class="form-select select_sub_cat" name="select_sub_cat[]"
                                            class="select_sub_cat">
                                            <option selected readonly disabled>{{ __('Select Sub category') . '--' }}
                                            </option>
                                        </select>
                                    </div>
                                </td>
                                <td class="px-4 py-2 border"><button
                                        class="remove-row inline-flex items-center px-4 py-2 mx-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-red-600 border border-transparent rounded-md hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-gray disabled:opacity-25">Remove</button>
                                </td>
                            </tr>
                            <!-- This Row is Use for Clone Rows and Ignored to display on initialize page -->

                            <!-- Edit Categories already exist starts -->
                            @if ($product->category->isNotEmpty())
                                @foreach ($product->category as $parentCat)
                                    <tr id="row-template" class="bg-gray-100">
                                        <td class="px-4 py-2 border">
                                            <div class="mb-4">
                                                <label for="parentcategory_name"
                                                    class="block mb-2 text-sm font-bold text-gray-700">{{ __('Parent category') }}
                                                    <span class="text-red-600">*</span></label>
                                                <select class="form-select select_parent_cat" name="select_parent_cat[]"
                                                    class="select_parent_cat">
                                                    <option selected readonly disabled>
                                                        {{ __('Select Parent category') . '--' }}</option>
                                                    @foreach ($parent_category as $parent_cat)
                                                        <option value="{{ $parent_cat->id }}"
                                                            {{ $parentCat->id == $parent_cat->id ? 'selected' : '' }}>
                                                            {{ $parent_cat->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 border">
                                            <div class="mb-4">
                                                <label for="subcategory_name"
                                                    class="block mb-2 text-sm font-bold text-gray-700">{{ __('Sub category') }}
                                                    <span class="text-red-600">*</span></label>
                                                <select class="form-select select_sub_cat" name="select_sub_cat[]"
                                                    class="select_sub_cat">
                                                    <option selected readonly disabled>
                                                        {{ __('Select Sub category') . '--' }}
                                                    </option>
                                                    @if ($parentCat->subcategories->isNotEmpty())
                                                        @foreach ($parentCat->subcategories as $subCat)
                                                            <option value="{{ $subCat->id }}"
                                                                {{ $parentCat->pivot->category_id == $parentCat->id && $parentCat->pivot->sub_category_id == $subCat->id ? 'selected' : '' }}>
                                                                {{ $subCat->name }} </option>
                                                        @endforeach <!-- Sub Category Loop Ends -->
                                                    @endif
                                                </select>
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 border"><button
                                                class="remove-row inline-flex items-center px-4 py-2 mx-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-red-600 border border-transparent rounded-md hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-gray disabled:opacity-25">Remove</button>
                                        </td>
                                    </tr>
                                @endforeach <!-- Parent Category Loop Ends -->
                            @endif
                            <!-- Edit Categories already exist Ends -->
                        </tbody>
                    </table>
                    <!-- KEY :: DYNAMICMULTIROW Ends -->

                    <div class="mb-4">
                        <label for="price" class="block mb-2 text-sm font-bold text-gray-700">Price <span
                                class="text-red-600">*</span></label>
                        <input type="text"
                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            name="price" min="1" maxlength="13" placeholder="Enter Product price"
                            value="{{ old('price', $product->price) }}" required>
                        @error('price')
                            <span class="text-red-600 text-danger">{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="qty" class="block mb-2 text-sm font-bold text-gray-700">Quantity <span
                                class="text-red-600">*</span></label>
                        <input type="number"
                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline numberonly"
                            name="qty" min="1" max="4294967295" placeholder="Enter Product Quantity"
                            value="{{ old('qty', $product->qty) }}" required>
                        @error('qty')
                            <span class="text-red-600 text-danger">{{ $message }}
                            </span>
                        @enderror
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
    {{-- KEY : DYNAMICMULTIROW Starts --}}
    @push('footer-scripts')
        <script type='text/javascript' src="{{ asset('js/jquery-3.6.4.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                // after image preview on close hide image
                $(document).on('click', '.img_close', function() {
                    var img_div_id = $(this).attr('data');
                    $('.img_' + img_div_id).hide();
                    if ($('#img_delete').val().length != 0) { // value not empty
                        var ids = [$('#img_delete').val(), img_div_id];
                    } else {
                        var ids = [img_div_id];
                    }

                    var idsString = ids.join(',');
                    $('#img_delete').val(idsString);
                });

                // Add Row Btn 
                $(document).on('click', '#add-row', function() {
                    var row = $('#row-template').clone();
                    row.removeAttr('id').show();
                    $('#dynamicAddRemoveTbl tbody').append(row);
                });
                // Remove Row Btn 
                $(document).on('click', '.remove-row', function() {
                    if ($('#dynamicAddRemoveTbl tbody tr').length > 2) { // Check if more than one row exists
                        $(this).closest('tr').remove(); // Remove only if multiple rows are present
                    } else { // Provide feedback to the user, such as displaying a message:
                        alert("Cannot remove the last row.");
                    }
                });

                // parent category on change bind sub category data by ajax
                $(document).on('change', '.select_parent_cat', function() {
                    var closestTr = $(this).closest('tr');
                    var selectOptions =
                        '<option selected="" readonly="" disabled="">Select Sub category-- </option>';
                    if ($(this).val() != '') { // check value is not empty then send ajax request
                        $.ajax({
                            type: 'POST',
                            url: '{{ url('/getsubcategories') }}',
                            data: {
                                category_id: $(this).val()
                            },
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                '_method': 'post'
                            },
                            beforeSend: function() { // Before ajax send operation
                                closestTr.find('.select_sub_cat').html(selectOptions);
                            },
                            success: function(data_resp, textStatus,
                                jqXHR) { // On ajax success operation
                                data_resp.data.forEach(function(valueObj, index) {
                                    selectOptions += '<option value="' + valueObj.id +
                                        '" >' +
                                        valueObj.name + '</option>'
                                });
                                // bind final options to select
                                closestTr.find('.select_sub_cat').html(selectOptions);
                            },
                            error: function(jqXHR, textStatus,
                                errorThrown) { // On ajax error operation 
                                closestTr.find('.select_sub_cat').html(selectOptions);
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
    {{-- KEY : DYNAMICMULTIROW Ends --}}
</x-app-layout>
