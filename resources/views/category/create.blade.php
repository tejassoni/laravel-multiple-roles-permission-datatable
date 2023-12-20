<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Create - Category
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <a title="back" href="{{ route('category.index') }}"
                    class="inline-flex items-center px-4 py-2 mb-4 text-xs font-semibold tracking-widest uppercase transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-gray disabled:opacity-25">
                    Back
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
                <form action="{{ route('category.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block mb-2 text-sm font-bold text-gray-700">Category Name <span
                                class="text-red-600">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Enter Category name"
                            maxlength="100" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="text-red-600 text-danger">{{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block mb-2 text-sm font-bold text-gray-700">Category
                            Description</label>
                        <textarea class="form-control" cols="40" rows="7" name="description"
                            placeholder="Enter Category description">{{ old('description') }}</textarea>
                    </div>
                    <div>
                        <button title="save" type="submit"
                            class="inline-flex items-center px-4 py-2 my-3 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
