<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Show - Parent Category') }}
      </h2>
  </x-slot>
  <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
              <a title="back" href="{{ route('category.index') }}"
                  class="inline-flex items-center px-4 py-2 mb-4 text-xs font-semibold tracking-widest uppercase transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-gray disabled:opacity-25">
                  {{ __('Back') }}
              </a>

              <div class="mb-4">
                  <label for="category_name"
                      class="block mb-2 text-sm font-bold text-gray-700"><b>{{ __('Category name') }} : </b><span>{{ $category->name }}</span> </label> 
              </div>
              <div class="mb-4">
                  <label for="category_description"
                      class="block mb-2 text-sm font-bold text-gray-700"><b>{{ __('Category Description') }} : </b><span>{{ $category->description }}</span> </label>
                      
              </div>
          </div>
      </div>
  </div>
</x-app-layout>