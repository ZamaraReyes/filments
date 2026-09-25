@auth
@unless(auth()->user()->hasVerifiedEmail())
<div class="bg-yellow-600 text-white rounded-md px-4 py-3 mb-4 text-sm flex flex-wrap items-center justify-between">
    <p class="my-1">
        @if(session('resent'))
            A new verification link has been sent to <strong>{{ auth()->user()->email }}</strong>.
        @else
            Please confirm your email address. We sent a verification link to <strong>{{ auth()->user()->email }}</strong>.
        @endif
    </p>
    <form method="POST" action="{{ route('verification.resend') }}" class="my-1">
        @csrf
        <button type="submit" class="bg-gray-900 hover:bg-gray-800 text-white py-2 px-4 rounded-full text-xs">
            Resend verification email
        </button>
    </form>
</div>
@endunless
@endauth
