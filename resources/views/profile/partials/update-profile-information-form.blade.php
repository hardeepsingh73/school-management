<section class="mb-5">
    <header class="mb-4">
        <h2 class="h5 fw-semibold text-dark">
            {{ __('Profile Information') }}
        </h2>
        <p class="mt-2 text-muted small">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label">{{ __('Name') }}</label>
            <input type="text" 
                   class="form-control" 
                   id="name" 
                   name="name" 
                   value="{{ old('name', $user->name) }}" 
                   required 
                   autofocus 
                   autocomplete="name">
            @if($errors->get('name'))
                <div class="invalid-feedback d-block">
                    {{ implode(', ', $errors->get('name')) }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email') }}</label>
            <input type="email" 
                   class="form-control" 
                   id="email" 
                   name="email" 
                   value="{{ old('email', $user->email) }}" 
                   required 
                   autocomplete="username">
            @if($errors->get('email'))
                <div class="invalid-feedback d-block">
                    {{ implode(', ', $errors->get('email')) }}
                </div>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3">
                    <p class="text-dark small mb-2">
                        {{ __('Your email address is unverified.') }}
                    </p>
                    <button form="send-verification" class="btn btn-link p-0 text-decoration-none">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2 mb-0 small">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3 mt-4">
            <button type="submit" class="btn btn-primary">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated'))
                <div class="text-success small" id="profileUpdateMessage">
                    {{ __('Saved.') }}
                </div>
                <script>
                    setTimeout(() => {
                        document.getElementById('profileUpdateMessage').style.display = 'none';
                    }, 2000);
                </script>
            @endif
        </div>
    </form>
</section>