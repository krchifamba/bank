<!-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('admin.accounts.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="user_id" class="block">User</label>
                        <select name="user_id" id="user_id" class="w-full p-2 rounded" required>
                            @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="number" class="block">Account Number</label>
                        <input type="text" name="number" id="number" class="w-full p-2 rounded" required>
                    </div>

                    <div>
                        <label for="type" class="block">Account Type</label>
                        <select name="type" id="type" class="w-full p-2 rounded" required>
                            <option value="checking">Checking</option>
                            <option value="savings">Savings</option>
                        </select>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded">Create Account</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout> -->