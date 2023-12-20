<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Show - User Details') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <a title="back" href="{{ route('users.index') }}"
                    class="inline-flex items-center px-4 py-2 mb-4 text-xs font-semibold tracking-widest text-black uppercase transition duration-150 ease-in-out bg-green-600 border border-transparent rounded-md hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:shadow-outline-gray disabled:opacity-25">
                    Go back
                </a>
                <div class="mb-4">
                    <label for="name"
                    class="block mb-2 text-sm font-bold text-gray-700"><b>{{ __('User name') }} : </b><span>{{ $user->name }}</span> </label> 
                </div>
                <div class="mb-4">
                    <label for="email"
                    class="block mb-2 text-sm font-bold text-gray-700"><b>{{ __('User email') }} : </b><span>{{ $user->email }}</span> </label> 
                </div>


                <div class="mb-4">
                    <label for="textrole" class="block mb-2 text-sm font-bold text-gray-700">Selected Role</label>
                    <select name="roles" id="roles"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        multiple readonly disabled>
                        @foreach ($user->getRoleNames() as $v)
                            <option value="{{ $v }}" selected>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
