<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Parent Category
        </h2>
    </x-slot>
    <!-- KEY : DATATABLE Starts Styles -->
    @push('header-styles')
        <link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
    @endpush
    <!-- KEY : DATATABLE Ends Styles -->

    <!-- Filter Search Starts -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('category.search') }}" method="GET">
                        @csrf
                        <div class="flex space-x-4">
                            <div class="flex-none w-14 h-14">
                                <div class="mb-4">
                                    <input type="text" name="name" class="form-control"
                                        placeholder="Enter Category name" maxlength="100" value="{{ request()->name }}">
                                </div>
                            </div>
                            <div class="flex-initial w-64 pl-3">
                                <div class="mb-4">
                                    <label for="status"
                                        class="block mb-2 text-sm font-bold text-gray-700">{{ __('Status') }}</label>
                                    <input type="radio" name="status"
                                        value="{{ App\Models\Category::STATUS_ACTIVE }}"
                                         @if (request()->has('status') && request()->status) == App\Models\Category::STATUS_ACTIVE) checked @endif /> Active
                                    <input type="radio" name="status"
                                        value="{{ App\Models\Category::STATUS_INACTIVE }}"
                                        @if (request()->has('status') && request()->status == App\Models\Category::STATUS_INACTIVE) checked @endif /> In-Active
                                </div>
                            </div>
                            <div class="flex-initial w-64 pl-3">
                                <button title="search" type="submit"
                                    class="inline-flex items-center px-4 py-2 my-3 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                                    Search
                                </button>

                                <a title="reset" href="{{ url('category') }}"
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
                    @can('category-create')
                        <a title="new" href="{{ route('category.create') }}"
                            class="inline-flex items-center px-4 py-2 mb-4 text-xs font-semibold tracking-widest text-black uppercase transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-gray disabled:opacity-25">Create
                            New Category</a>
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
                                <th class="px-4 py-2 border">Status</th>
                                <th class="px-4 py-2 border">Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $category->name }}</td>
                                    <td class="px-4 py-2 border">{{ $category->description }}</td>
                                    <td class="px-4 py-2 border">
                                        <input type="radio" name="status_{{ $category->id }}"
                                            data-id="{{ $category->id }}"
                                            value="{{ App\Models\Category::STATUS_ACTIVE }}"
                                            @if ($category->status) checked @endif class="status" /> Active
                                        </br>
                                        <input class="status" type="radio" name="status_{{ $category->id }}"
                                            data-id="{{ $category->id }}"
                                            value="{{ App\Models\Category::STATUS_INACTIVE }}"
                                            @if (!$category->status) checked @endif /> In-Active
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <form action="{{ route('category.destroy', $category->id) }}" method="POST">
                                            <!-- KEY : MULTIPERMISSION starts -->
                                            @can('category-show')
                                                <a title="show"
                                                    class="inline-flex items-center px-4 py-2 mx-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-500 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25"
                                                    href="{{ route('category.show', $category->id) }}">Show</a>
                                            @endcan
                                            @can('category-edit')
                                                <a title="edit"
                                                    class="inline-flex items-center px-4 py-2 mx-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25"
                                                    href="{{ route('category.edit', $category->id) }}">Edit</a>
                                            @endcan
                                            @can('category-delete')
                                                @csrf
                                                @method('DELETE')
                                                <button title="delete" type="submit"
                                                    class="inline-flex items-center px-4 py-2 mx-2 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-red-600 border border-transparent rounded-md hover:bg-red-500 active:bg-red-700 focus:outline-none focus:border-red-700 focus:shadow-outline-gray disabled:opacity-25"
                                                    onclick="return confirm('Are you sure you want to delete this ?');">Delete</button>
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
                        url: "{{ url('category-status') }}",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            category_id: $(this).data('id'),
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
