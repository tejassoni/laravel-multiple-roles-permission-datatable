<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Parent Category
        </h2>
    </x-slot>

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
                    <table id="category_tbl" class="w-full table-fixed" style="width:100%">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 border">Name</th>
                                <th class="px-4 py-2 border">Description</th>
                                <th class="px-4 py-2 border">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="px-4 py-2 border">{{ $category->name }}</td>
                                    <td class="px-4 py-2 border">{{ $category->description }}</td>
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
</x-app-layout>
