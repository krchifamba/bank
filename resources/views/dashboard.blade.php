<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
            @endif

            <div class="bg-white m-4 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('Welcome,') }} {{ Auth::user()->first_name }}!
                </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('You have ') }} {{ $accounts->count() }} {{ __('account(s).') }}
                </div>
            </div>

            <!-- Make a Transfer Button -->
            <div class="py-4">
                <button onclick="toggleTransferForm()" class="bg-blue-600 border hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                    {{ __('Make a Transfer') }}
                </button>

                <!-- Hidden Transfer Form -->
                <div id="transferForm" class="mt-6 hidden">
                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                        <form action="{{ route('transfer.store') }}" method="POST">
                            @csrf

                            <!-- From Account -->
                            <div class="mb-4">
                                <label for="from_account_id" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                                    From Account
                                </label>
                                <select name="from_account_id" id="from_account_id" class="w-full p-2 rounded" required>
                                    @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">
                                        {{ __('Your Balance:') }}
                                        @if ($selectedCurrency !== 'USD')
                                        {{ $currencySymbol }}{{ $convertedPerAccount[$account->id][$selectedCurrency] ?? $account->balance }}
                                        @else
                                        {{ $currencySymbol }}{{ $account->balance }}
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- To Account Number -->
                            <div class="mb-4">
                                <label for="to_account_number" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                                    To Account Number
                                </label>
                                <input type="text" name="to_account_number" id="to_account_number" placeholder="Enter receiver's Account Number" class="w-full p-2 rounded" required>
                            </div>

                            <!-- Amount (In session currency) -->
                            <div class="mb-4">
                                <label for="amount" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                                    Amount
                                </label>
                                <input type="number" name="amount" id="amount" step="0.01" min="0.01" class="w-full p-2 rounded" required>
                            </div>

                            
                            <div class="flex items-center">
                                <select name="currency" id="currency" class="w-full p-2 rounded">
                                    <option value="USD" selected>USD</option>
                                    <option value="EUR">EUR</option>
                                    <option value="GBP">GBP</option>
                                </select>
                            </div>

                            <!-- Description -->
                            <div class="mb-6">
                                <label for="description" class="block text-gray-700 dark:text-gray-300 font-bold mb-2">
                                    Description (Optional)
                                </label>
                                <input type="text" name="description" id="description" class="w-full p-2 rounded">
                            </div>


                            <!-- Submit Button -->
                            <div class="flex justify-end">
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    Confirm Transfer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($accounts as $account)
                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 mb-4">
                        <p class="text-gray-800 dark:text-gray-200 flex justify-between">
                            <span>{{ __('Account Type:') }} {{ $account->type }}</span>
                            <span>{{ __('Account Number:') }} {{ $account->number }}</span>
                        </p>
                        <h3 class="text-lg font-semibold text-gray-600 dark:text-gray-400 mt-4">
                            {{ __('Your Balance:') }}
                            @if ($selectedCurrency !== 'USD')
                            {{ $currencySymbol }}{{ $convertedPerAccount[$account->id][$selectedCurrency] ?? $account->balance }}
                            @else
                            {{ $currencySymbol }}{{ $account->balance }}
                            @endif
                        </h3>
                    </div>
                    @endforeach
                </div>
            </div>



        </div>
    </div>

    <!-- Toggle Transfer Form Script -->
    <script>
        function toggleTransferForm() {
            var form = document.getElementById('transferForm');
            form.classList.toggle('hidden');
        }
    </script>

</x-app-layout>