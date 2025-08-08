<section class="mb-5">
    <header class="mb-4">
        <h2 class="h5 fw-semibold text-dark">
            {{ __('Update Password') }}
        </h2>
        <p class="mt-2 text-muted small">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label">
                {{ __('Current Password') }}
            </label>
            <input type="password" 
                   class="form-control" 
                   id="update_password_current_password" 
                   name="current_password" 
                   autocomplete="current-password">
            @if($errors->updatePassword->get('current_password'))
                <div class="invalid-feedback d-block">
                    {{ implode(', ', $errors->updatePassword->get('current_password')) }}
                </div>
            @endif
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label">
                {{ __('New Password') }}
            </label>
            <input type="password" 
                   class="form-control" 
                   id="update_password_password" 
                   name="password" 
                   autocomplete="new-password">
            @if($errors->updatePassword->get('password'))
                <div class="invalid-feedback d-block">
                    {{ implode(', ', $errors->updatePassword->get('password')) }}
                </div>
            @endif
        </div>

        <div class="mb-4">
            <label for="update_password_password_confirmation" class="form-label">
                {{ __('Confirm Password') }}
            </label>
            <input type="password" 
                   class="form-control" 
                   id="update_password_password_confirmation" 
                   name="password_confirmation" 
                   autocomplete="new-password">
            @if($errors->updatePassword->get('password_confirmation'))
                <div class="invalid-feedback d-block">
                    {{ implode(', ', $errors->updatePassword->get('password_confirmation')) }}
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated'))
                <div class="text-success small" id="passwordUpdateMessage">
                    {{ __('Saved.') }}
                </div>
                <script>
                    setTimeout(() => {
                        document.getElementById('passwordUpdateMessage').style.display = 'none';
                    }, 2000);
                </script>
            @endif
        </div>
    </form>
</section>