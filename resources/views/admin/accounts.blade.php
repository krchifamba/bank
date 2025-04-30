<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Accounts List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div x-data="{ open: false }">
                <!-- Create Account Button -->
                <div class="flex justify-end mb-4">
                    <button
                        @click="open = true"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        + Create New Account
                    </button>
                </div>

                <!-- Modal -->
                <div
                    x-show="open"
                    @keydown.escape.window="open = false"
                    class="relative z-10"
                    aria-labelledby="modal-title"
                    role="dialog"
                    aria-modal="true">
                    <!-- Background overlay -->
                    <div
                        x-show="open"
                        x-transition.opacity
                        class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity"></div>

                    <!-- Modal panel -->
                    <div
                        x-show="open"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        class="fixed inset-0 flex items-center justify-center p-4 overflow-y-auto">
                        <div class="bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all max-w-lg w-full">
                            <div class="px-6 py-4">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Create New Account</h2>

                                <form method="POST" action="{{ route('admin.accounts.create') }}">
                                    @csrf

                                    <!-- First Name -->
                                    <div class="mb-4">
                                        <label for="first_name" class="block text-gray-700 dark:text-gray-300">First Name</label>
                                        <input type="text" name="first_name" id="first_name" required class="w-full p-2 rounded border dark:bg-gray-700 dark:border-gray-600">
                                    </div>

                                    <!-- Last Name -->
                                    <div class="mb-4">
                                        <label for="last_name" class="block text-gray-700 dark:text-gray-300">Last Name</label>
                                        <input type="text" name="last_name" id="last_name" required class="w-full p-2 rounded border dark:bg-gray-700 dark:border-gray-600">
                                    </div>

                                    <!-- Date of Birth -->
                                    <div class="mb-4">
                                        <label for="dob" class="block text-gray-700 dark:text-gray-300">Date of Birth</label>
                                        <input type="date" name="dob" id="dob" required class="w-full p-2 rounded border dark:bg-gray-700 dark:border-gray-600">
                                    </div>

                                    <!-- Email -->
                                    <div class="mb-4">
                                        <label for="email" class="block text-gray-700 dark:text-gray-300">Email</label>
                                        <input type="email" name="email" id="email" required class="w-full p-2 rounded border dark:bg-gray-700 dark:border-gray-600">
                                    </div>

                                    <!-- Address -->
                                    <div class="mb-4">
                                        <label for="address" class="block text-gray-700 dark:text-gray-300">Address</label>
                                        <input type="text" name="address" id="address" required class="w-full p-2 rounded border dark:bg-gray-700 dark:border-gray-600">
                                    </div>

                                    <!-- Account Type -->
                                    <div class="mb-4">
                                        <label for="type" class="block text-gray-700 dark:text-gray-300">Account Type</label>
                                        <select name="type" id="type" required class="w-full p-2 rounded border dark:bg-gray-700 dark:border-gray-600">
                                            <option value="savings">Savings</option>
                                            <option value="current">Current</option>
                                        </select>
                                    </div>

                                    <!-- Buttons -->
                                    <div class="flex justify-end">
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Create
                                        </button>
                                        <button type="button" @click="open = false" class="ml-2 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Accounts Table -->
            @if ($accounts->count())
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg mt-6">
                <table class="min-w-full table-auto text-sm text-left text-gray-800 dark:text-gray-200">
                    <thead class="min-w-full bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-3 w-1/6 whitespace-nowrap">Name</th>
                            <th class="px-4 py-3 w-1/6 whitespace-nowrap">Email</th>
                            <th class="px-4 py-3 w-1/6 whitespace-nowrap">Account #</th>
                            <th class="px-4 py-3 w-1/6 whitespace-nowrap">Type</th>
                            <th class="px-4 py-3 w-1/6 whitespace-nowrap">Balance</th>
                            <th class="px-4 py-3 w-1/4 whitespace-nowrap">Address</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($accounts as $account)
                        <tr>
                            <td class="px-4 py-3 break-words truncate max-w-xs">{{ $account->name }}</td>
                            <td class="px-4 py-3 break-words truncate max-w-xs">{{ $account->email }}</td>
                            <td class="px-4 py-3 break-words truncate">{{ $account->number }}</td>
                            <td class="px-4 py-3 capitalize">{{ $account->type }}</td>
                            <td class="px-4 py-3">${{ number_format($account->balance, 2) }}</td>
                            <td class="px-4 py-3 break-words truncate max-w-sm">{{ $account->address }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $accounts->links() }}
                </div>
            </div>
            @else
            <p class="text-gray-600 dark:text-gray-400 mt-6">No accounts found.</p>
            @endif

        </div>
    </div>
</x-app-layout>