@auth
@unless(auth()->user()->hasVerifiedEmail())
<div
    x-data="{ open: true }"
    x-init="setTimeout(() => open = false, 15000)"
    x-show="open"
    x-transition
    class="fixed top-4 inset-x-4 sm:inset-x-auto sm:right-4 z-50 flex flex-wrap items-center justify-between gap-2 bg-yellow-600 text-white rounded-md px-4 py-3 text-sm shadow-2xl sm:max-w-sm"
>
    <p class="my-1">
        Please confirm your email address. We sent a verification link to <strong>{{ auth()->user()->email }}</strong>.
    </p>
    <form method="POST" action="{{ route('verification.resend') }}" class="my-1">
        @csrf
        <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white py-2 px-4 rounded-full text-xs">
            Resend verification email
        </button>
    </form>
    <button @click="open = false" class="absolute top-2 right-3 text-white text-lg leading-none">&times;</button>
</div>
@endunless
@endauth
