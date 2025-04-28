<x-app-layout>
    <div class="py-12" x-data="{ showForm: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Button -->
            <div class="mb-4">
                <button 
                    @click="showForm = !showForm"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Make a Transfer
                </button>
            </div>

            <!-- Hidden Form -->
            <div x-show="showForm" x-transition class="bg-white p-6 rounded-lg shadow-lg dark:bg-gray-800">
                <form action="{{ route('transfer.store') }}" method="POST">
                    @csrf

                    <!-- From Account -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200">From Account</label>
                        <select name="from_account_id" class="mt-1 block w-full rounded-md">
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}">
                                    {{ $account->number }} - Balance: ${{ number_format($account->balance, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- To Account -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200">To Account Number</label>
                        <input type="text" name="to_account_number" class="mt-1 block w-full rounded-md" required>
                    </div>

                    <!-- Amount -->
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-200">Amount</label>
                        <input type="number" name="amount" step="0.01" class="mt-1 block w-full rounded-md" required>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Send Money
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
