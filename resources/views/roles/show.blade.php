<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Show - Roles Permissions') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <a title="back" href="{{ route('roles.index') }}"
                    class="inline-flex items-center px-4 py-2 mb-4 text-xs font-semibold tracking-widest text-black uppercase transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-gray disabled:opacity-25">
                    Go back
                </a>
                <div class="mb-4">
                    <label for="name"
                      class="block mb-2 text-sm font-bold text-gray-700"><b>{{ __('Role name') }} : </b><span>{{ $role->name }}</span> </label> 
                </div>
                <div class="mb-4">
                    @foreach ($rolePermissions as $permission)
                        <button type="button"
                            class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">{{ ucwords($permission->name) }}</button><br>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
