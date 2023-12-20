<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create - User') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div title="back" class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                <a href="{{ route('users.index') }}"
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
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="textname" class="block mb-2 text-sm font-bold text-gray-700">Name <span
                                class="text-red-600">*</span></label>
                        <input type="text"
                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            name="name" placeholder="Enter name" value="{{ old('name') }}" required>
                        @error('name')
                            <span class="text-red-600">{{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="textemail" class="block mb-2 text-sm font-bold text-gray-700">Email <span
                                class="text-red-600">*</span></label>
                        <input type="text"
                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            name="email" placeholder="Enter email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="text-red-600">{{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="textpassword" class="block mb-2 text-sm font-bold text-gray-700">Password <span
                                class="text-red-600">*</span></label>
                        <input type="password"
                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            name="password" placeholder="Enter Password" value="{{ old('password') }}" required>
                        @error('password')
                            <span class="text-red-600">{{ $message }}
                            </span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="textconfirmpassword" class="block mb-2 text-sm font-bold text-gray-700">Confirm
                            Password <span class="text-red-600">*</span></label>
                        <input type="password"
                            class="w-full px-3 py-2 leading-tight text-gray-700 border rounded shadow appearance-none focus:outline-none focus:shadow-outline"
                            name="password_confirmation" placeholder="Enter Confirm Password" required>
                        @error('password_confirmation')
                            <span class="text-red-600">{{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="textrole" class="block mb-2 text-sm font-bold text-gray-700">Select Roles <span
                                class="text-red-600">*</span></label>
                        <select name="roles[]" id="roles[]"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            multiple required>
                            <option disabled readonly>Choose a role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @if (old('roles') && in_array($role->name, old('roles'))) selected @endif>
                                    {{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('roles')
                            <span class="text-red-600">{{ $message }}
                            </span>
                        @enderror
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
