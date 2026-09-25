<div
    x-data="{ open: true }"
    x-init="setTimeout(() => open = false, 15000)"
    x-show="open"
    x-transition
    class="fixed top-4 inset-x-4 sm:inset-x-auto sm:right-4 z-50 flex items-center justify-between gap-4 bg-green-600 text-white px-5 py-3 rounded-lg shadow-2xl sm:max-w-sm"
>
    <p class="text-sm font-400">{{ session('status') }}</p>
    <button @click="open = false" class="text-white text-lg leading-none">&times;</button>
</div>
