<div class="bg-white p-8 rounded shadow-md w-full max-w-md mx-auto mt-20">

    <h1 class="text-2xl font-bold text-red-600 mb-4 text-center">حذف الحساب</h1>
    <p class="text-gray-700 mb-6 text-center">
        إذا كنت ترغب في حذف حسابك، الرجاء إدخال رقم الجوال المرتبط بالحساب.
    </p>

    @if($successMessage)
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ $successMessage }}
        </div>
    @endif

    @if($errorMessage)
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            {{ $errorMessage }}
        </div>
    @endif

    <form wire:submit.prevent="deleteAccount">
        <label for="phone" class="block mb-2 text-sm font-medium">رقم الجوال:</label>
        <input type="text" wire:model.defer="phone" id="phone" placeholder="05XXXXXXXX"
               class="w-full p-2 border border-gray-300 rounded mb-4 @error('phone') border-red-500 @enderror">

        @error('phone')
            <p class="text-red-500 text-sm mb-4">{{ $message }}</p>
        @enderror

        <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 w-full rounded">
            حذف الحساب نهائيًا
        </button>
    </form>
</div>
