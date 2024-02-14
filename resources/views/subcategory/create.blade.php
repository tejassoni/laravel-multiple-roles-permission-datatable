<x-app-layout>
    <!-- Header Section Starts -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create - Sub Category') }}
        </h2>
    </x-slot>
    <!-- Header Section Ends -->

    <!-- Form Div Section Starts -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <a title="back" href="{{ route('subcategory.index') }}"
                    class="inline-flex items-center px-4 py-2 mb-4 text-xs font-semibold tracking-widest uppercase transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-gray disabled:opacity-25">
                    {{ __('Back') }}
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
                <!-- Create Form Starts -->
                <form action="{{ route('subcategory.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="name"
                            class="block mb-2 text-sm font-bold text-gray-700">{{ __('Sub Category Name') }} <span
                                class="text-red-600">*</span></label>
                        <input type="text" name="name" class="form-control"
                            placeholder="{{ __('Enter Sub category name') }}" maxlength="100"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <span class="text-red-600 text-danger">{{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description"
                            class="block mb-2 text-sm font-bold text-gray-700">{{ __('Sub Category Description') }}
                        </label>
                        <textarea class="form-control" cols="40" rows="7" name="description"
                            placeholder="{{ __('Enter Sub category description') }}">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label for="select_parent_cat"
                            class="block mb-2 text-sm font-bold text-gray-700">{{ __('Parent category') }} <span
                                class="text-red-600">*</span></label>
                        <select class="form-select" name="select_parent_cat[]" id="select_parent_cat" required multiple>
                            <option readonly disabled>{{ __('Select Parent category') . '--' }}</option>
                            @foreach ($parent_category as $parent_cat)
                                <option value="{{ $parent_cat->id }}"
                                    @if (old('select_parent_cat') && in_array($parent_cat->id, old('select_parent_cat'))) selected @endif >
                                    {{ $parent_cat->name }}</option>
                            @endforeach
                        </select>
                        @error('select_parent_cat')
                            <span class="text-red-600 text-danger">{{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="status"
                            class="block mb-2 text-sm font-bold text-gray-700">{{ __('Status') }}</label>
                            <input type="radio" name="status" 
                            value="{{ App\Models\SubCategory::STATUS_ACTIVE }}"
                            @if (old('status') == App\Models\SubCategory::STATUS_ACTIVE) checked @endif class="status" /> Active 
                        <input class="status" type="radio" name="status" 
                            value="{{ App\Models\SubCategory::STATUS_INACTIVE }}"
                            @if (old('status') == App\Models\SubCategory::STATUS_INACTIVE) checked @endif /> In-Active
                    </div>
                    <div>
                        <button type="submit" title="save"
                            class="inline-flex items-center px-4 py-2 my-3 text-xs font-semibold tracking-widest text-white uppercase transition duration-150 ease-in-out bg-gray-800 border border-transparent rounded-md hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
                <!-- Create Form Ends -->
            </div>
        </div>
    </div>
    <!-- Form Div Section Ends -->

</x-app-layout>
