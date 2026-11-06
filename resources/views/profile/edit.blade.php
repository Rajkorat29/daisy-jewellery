@extends('layouts.app')

@section('title', 'My Profile — ' . \App\Models\Setting::get('site_name', 'Daisy'))

@section('content')
<main class="wrap" style="padding-top:1.5rem;padding-bottom:3rem">
  <p class="crumbs mono" style="margin-bottom:1.5rem">
    <a href="{{ url('/') }}">Home</a> / Account / Profile
  </p>

  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem">
    <h1 style="font-size:clamp(2rem,4vw,3rem);font-weight:800;letter-spacing:-.03em">My Profile</h1>

    <div style="display:flex;gap:.6rem;flex-wrap:wrap;align-items:center">
      <a class="btn {{ request()->routeIs('account.orders*') ? 'solid' : '' }}" href="{{ route('account.orders') }}">Orders</a>
      <a class="btn {{ request()->routeIs('account.addresses*') ? 'solid' : '' }}" href="{{ route('account.addresses') }}">Addresses</a>
      <a class="btn {{ request()->routeIs('account.profile*') || request()->routeIs('profile.*') ? 'solid' : '' }}" href="{{ route('account.profile') }}">Profile</a>

      <form method="POST" action="{{ route('logout') }}" style="margin:0">
        @csrf
        <button type="submit" class="btn pink">
          Log out
        </button>
      </form>
    </div>
  </div>

  <!-- Account Overview & Quick Logout Card -->
  <div class="page-box" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1.2rem">
    <div>
      <span class="mono" style="color:var(--muted);font-size:.78rem">Signed in as</span>
      <h2 style="font-size:1.5rem;font-weight:800;margin:.2rem 0">{{ $user->name }}</h2>
      <p class="mono" style="margin:0;color:var(--muted);font-size:.88rem">
        {{ $user->email }}{{ $user->phone ? ' · ' . $user->phone : '' }}
      </p>
    </div>

    <div style="display:flex;gap:.75rem;flex-wrap:wrap;align-items:center">
      @if($user->isAdmin())
        <a href="{{ route('admin.dashboard') }}" class="btn">Admin Panel</a>
      @endif

      <form method="POST" action="{{ route('logout') }}" style="margin:0">
        @csrf
        <button type="submit" class="btn pink" style="padding:.8rem 1.5rem">
          Log out of account
        </button>
      </form>
    </div>
  </div>

  <div class="split-2col">
    <!-- Left: Update Profile Information -->
    <div class="page-box" style="margin-bottom:0">
      <h2 style="font-size:1.35rem;font-weight:700;margin-bottom:.3rem">Profile Information</h2>
      <p class="muted" style="margin:0 0 1.2rem;font-size:.92rem">
        Update your account's profile details, phone number, and email address.
      </p>

      <form method="POST" action="{{ route('account.profile.update') }}" class="form">
        @csrf
        @method('patch')

        <div class="fld">
          <label for="pName">Full Name *</label>
          <input class="in" id="pName" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name">
          @error('name')
            <p class="err">{{ $message }}</p>
          @enderror
        </div>

        <div class="fld">
          <label for="pEmail">Email Address *</label>
          <input class="in" id="pEmail" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
          @error('email')
            <p class="err">{{ $message }}</p>
          @enderror
        </div>

        <div class="fld">
          <label for="pPhone">Mobile Number</label>
          <input class="in" id="pPhone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" maxlength="15" placeholder="e.g. 9876543210">
          @error('phone')
            <p class="err">{{ $message }}</p>
          @enderror
        </div>

        <button type="submit" class="btn solid" style="justify-self:start;margin-top:.4rem">
          Save changes
        </button>
      </form>
    </div>

    <!-- Right: Update Password -->
    <div class="page-box" style="margin-bottom:0">
      <h2 style="font-size:1.35rem;font-weight:700;margin-bottom:.3rem">Update Password</h2>
      <p class="muted" style="margin:0 0 1.2rem;font-size:.92rem">
        Ensure your account is using a strong password to stay secure.
      </p>

      @if (session('status') === 'password-updated')
        <div style="background:var(--emerald);color:var(--on-strong);padding:.65rem 1rem;border-radius:12px;margin-bottom:1rem;font-weight:600;font-size:.9rem">
          Password updated successfully.
        </div>
      @endif

      <form method="POST" action="{{ route('password.update') }}" class="form">
        @csrf
        @method('put')

        <div class="fld">
          <label for="current_password">Current Password *</label>
          <input class="in" id="current_password" name="current_password" type="password" autocomplete="current-password" required>
          @if($errors->updatePassword->has('current_password'))
            <p class="err">{{ $errors->updatePassword->first('current_password') }}</p>
          @endif
        </div>

        <div class="fld">
          <label for="new_password">New Password *</label>
          <input class="in" id="new_password" name="password" type="password" autocomplete="new-password" required>
          @if($errors->updatePassword->has('password'))
            <p class="err">{{ $errors->updatePassword->first('password') }}</p>
          @endif
        </div>

        <div class="fld">
          <label for="password_confirmation">Confirm New Password *</label>
          <input class="in" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
          @if($errors->updatePassword->has('password_confirmation'))
            <p class="err">{{ $errors->updatePassword->first('password_confirmation') }}</p>
          @endif
        </div>

        <button type="submit" class="btn solid" style="justify-self:start;margin-top:.4rem">
          Update password
        </button>
      </form>
    </div>
  </div>
</main>
@endsection
