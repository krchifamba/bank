<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-md rounded-xl">
        <h2 class="text-2xl font-semibold text-center mb-4">Two-Factor Authentication</h2>

        @if ($errors->any())
        <div class="mb-4 text-red-600 text-sm">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('2fa.verify') }}">
            @csrf

            <div class="mb-4">
                <label for="two_factor_code" class="block text-sm font-medium text-gray-700">
                    Enter the 6-digit code sent to your email
                </label>
                <input
                    id="two_factor_code"
                    name="two_factor_code"
                    type="text"
                    inputmode="numeric"
                    pattern="\d{6}"
                    maxlength="6"
                    required
                    autofocus
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="123456">
            </div>

            <div class="mt-6">
                <button
                    type="submit"
                    class="w-full py-2 px-4 bg-blue-600 text-black font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 appearance-none transition-colors duration-200">
                    Verify Code
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>