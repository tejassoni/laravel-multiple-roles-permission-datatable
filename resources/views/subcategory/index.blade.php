<x-app-layout>
    <!-- Header Section Starts -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Sub Category
        </h2>
    </x-slot>
    <!-- Header Section Ends -->

    <!-- KEY : DATATABLE Styles Starts -->
    @push('header-styles')
        <link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
    @endpush
    <!-- KEY : DATATABLE Styles Ends -->

    <!-- Filter Search Starts -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('subcategory.search') }}" method="GET">
                        @csrf
                        <div class="flex space-x-4">
                            <div class="flex-none w-14 h-14">
                                <div class="mb-4">
                                    <input type="text" name="parentcategoryname" class="form-control"
                                        placeholder="Enter Parent Category name" maxlength="100" value="{{ request()->parentcategoryname }}">
                                </div>
                            </div>
                            <div class="flex-none w-14 h-14 pl-3">
                                <div class="mb-4">
                                    <input type="text" name="subcategoryname" class="form-control"
                                        placeholder="Enter Sub Category name" maxlength="100" value="{{ request()->subcategoryname }}">
                                </div>
                            </div>
                            <div class="flex-none w-14 h-14 pl-3">
                                <div class="mb-4">
                                    <input type="text" name="createdby" class="form-control"
                                        placeholder="Enter Created By" maxlength="100" value="{{ request()->createdby }}">
                                </div>
                            </div>
                            <div class="flex-initial w-64 pl-3">
                                <div class="mb-4">
                                    <label for="status"
                                        class="block mb-2 text-sm font-bold text-gray-700">{{ __('Select Status') }}</label>
                                    <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <option disabled readonly selected>Select Status</option>
                                            <option value="{{ App\Models\SubCategory::STATUS_ACTIVE }}" @if(request()->has('status') && request()->status == App\Models\SubCategory::STATUS_ACTIVE) selected @endif>Active</option>
                                            <option value="{{ App\Models\SubCategory::STATUS_INACTIVE }}" @if(request()->has('status') && request()->status == App\Models\SubCategory::STATUS_INACTIVE) selected @endif>In-Active</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex-initial w-64 pl-3">
                                <button title="search" type="submit"
                                    class="inline-flex items-center px-4 py-2 my-3 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                                    Search
                                </button>

                                <a title="reset" href="{{ url('subcategory') }}"
                                    class="inline-flex items-center px-4 py-2 my-3 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Filter Search Ends -->

    <!-- Tables Lists Starts -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                     <!-- KEY : MULTIPERMISSION starts -->
                    @can('subcategory-create')
                        <a title="new" href="{{ route('subcategory.create') }}"
                            class="inline-flex items-center px-4 py-2 mb-4 text-xs font-semibold tracking-widest text-black uppercase transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-gray disabled:opacity-25">Create
                            New Sub Category</a>
                    @endcan
                     <!-- KEY : MULTIPERMISSION ends -->
                    <!-- Calls when session success triggers starts -->
                    @if (session('success'))
                        <div class="alert alert-success bg-green-100 border-t-4 border-green-500 rounded-b text-green-600 px-4 py-3 shadow-md my-3"
                            role="alert">
                            <div class="flex">
                                <div>
                                    <p class="text-sm text-success">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <!-- Calls when session success triggers ends -->
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

                    <!-- Dynamic Notification Alert Ajax Starts -->
                    <div id="notification-alert"></div>
                    <!-- Dynamic Notification Alert Ajax Ends -->
                    
                    <!-- KEY : DATATABLE Table ID and Class -->
                    <table id="tbl" class="w-full table-fixed display cell-border row-border stripe">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 border">Name</th>
                                <th class="px-4 py-2 border">Description</th>
                                <th class="px-4 py-2 border">Parent-category</th>
                                <th class="px-4 py-2 border">Created-by</th>
                                <th class="px-4 py-2 border">Status</th>
                                <th class="px-4 py-2 border">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subcategories as $subCat)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $subCat->name }}</td>
                                    <td class="px-4 py-2 border">{{ $subCat->description }}</td>
                                    <td class="px-4 py-2 border">@php if($subCat->parentcategories){
                                        foreach($subCat->parentcategories as $keyParCat => $valParCat ) { 
                                            echo $valParCat->name." , ";
                                        } // Loops Ends
                                    } 
                                    @endphp
                                    </td>
                                    <td class="px-4 py-2 border">{{ $subCat->getCatUserHasOne->name ?? 'None' }}</td>
                                    <td class="px-4 py-2 border">
                                        <input type="radio" name="status_{{ $subCat->id }}"
                                            data-id="{{ $subCat->id }}"
                                            value="{{ App\Models\SubCategory::STATUS_ACTIVE }}"
                                            @if ($subCat->status) checked @endif class="status" /> Active
                                        </br>
                                        <input class="status" type="radio" name="status_{{ $subCat->id }}"
                                            data-id="{{ $subCat->id }}"
                                            value="{{ App\Models\SubCategory::STATUS_INACTIVE }}"
                                            @if (!$subCat->status) checked @endif /> In-Active
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <form action="{{ route('subcategory.destroy', $subCat->id) }}" method="POST">
                                             <!-- KEY : MULTIPERMISSION starts -->
                                            @can('subcategory-show')
                                                <a title="show"
                                                    class="inline-flex items-center px-4 py-2 mx-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-500 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25"
                                                    href="{{ route('subcategory.show', $subCat->id) }}">{{ __('Show') }}</a>
                                            @endcan
                                            @can('subcategory-edit')
                                                <a title="edit"
                                                    class="inline-flex items-center px-4 py-2 mx-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25"
                                                    href="{{ route('subcategory.edit', $subCat->id) }}">{{ __('Edit') }}</a>
                                            @endcan
                                            @can('subcategory-delete')
                                                @csrf
                                                @method('DELETE')
                                                <button title="delete" type="submit"
                                                    class="inline-flex items-center px-4 py-2 mx-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-red-600 border border-transparent rounded-md hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-gray disabled:opacity-25"
                                                    onclick="return confirm('Are you sure you want to delete this ?');">{{ __('Delete') }}</button>
                                            @endcan
                                             <!-- KEY : MULTIPERMISSION ends -->
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Tables Lists Ends -->

    {{-- KEY : DATATABLE Scripts Starts --}}
    @push('footer-scripts')
        <script type='text/javascript' src="{{ asset('js/jquery-3.6.4.min.js') }}"></script>
        <script type='text/javascript' src="{{ asset('js/datatables.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#tbl').DataTable();

                $(document).on('change', '.status', function() {
                    $.ajax({
                        type: 'POST', // Default GET
                        url: "{{ url('subcategory-status') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            subcategory_id: $(this).data('id'),
                            status: $(this).val()
                        },
                        dataType: 'json', // text , XML, HTML
                        beforeSend: function() { // Before ajax send operation 
                            $("#notification-alert").html("");
                        },
                        success: function(data_resp, textStatus, jqXHR) { // On ajax success operation
                            if (data_resp.status) {
                                $("#notification-alert").html(
                                    "<div class='alert alert-success bg-green-100 border-t-4 border-green-500 rounded-b text-green-600 px-4 py-3 shadow-md my-3' role='alert'><div class='flex'> <div><p class='text-sm text-success'>" +
                                    data_resp.message + "</p></div></div></div>");
                            } else {
                                $("#notification-alert").html(
                                    "<div class='alert alert-danger rounded-b text-red-600 px-4 py-3 shadow-md my-3' role='alert'><div class='flex'> <div><p class='text-sm text-danger'>" +
                                    data_resp.message + "</p></div></div></div>");
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) { // On ajax error operation
                            $("#notification-alert").html(
                                "<div class='alert alert-danger rounded-b text-red-600 px-4 py-3 shadow-md my-3' role='alert'><div class='flex'> <div><p class='text-sm text-danger'>" +
                                jqXHR.responseJSON.message + "</p></div></div></div>");
                        },
                        complete: function() { // On ajax complete operation  
                            $('html, body').animate({
                                scrollTop: 0
                            }, 'slow');
                            $('#notification-alert').fadeOut(5000, function() {
                                $(this).html('').show();
                            });

                        }
                    });
                });
            });
        </script>
    @endpush
    {{-- KEY : DATATABLE Scripts Ends --}}
</x-app-layout>
