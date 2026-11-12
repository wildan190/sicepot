<x-guest-layout>
    <div class="w-full max-w-md mx-auto">
        <div class="ant-card p-6 sm:p-8">
            <div class="ant-alert ant-alert-info mb-4">
                <span>{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</span>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="ant-alert ant-alert-success mb-4">
                    <span class="font-medium">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </span>
                </div>
            @endif

            <div class="mt-4 flex items-center justify-between">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <div>
                        <x-primary-button>
                            {{ __('Resend Verification Email') }}
                        </x-primary-button>
                    </div>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="text-sm text-ant-secondary hover:text-primary underline transition-colors focus:outline-none">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
