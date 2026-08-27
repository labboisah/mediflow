<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" novalidate class="needs-validation">
        @csrf

        <h5 style="margin-bottom: 1.5rem; color: #27AE60; font-weight: 700;" class="d-flex align-items-center justify-content-center">
            Login <i class="bi bi-box-arrow-in-right"></i>
        </h5>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <div class="input-group">
                <span class="input-group-text" style="background: #f8f9fa; border: 2px solid #e0e0e0;">
                    <i class="bi bi-envelope" style="color: #FF8C42;"></i>
                </span>
                <input 
                    id="email" 
                    class="form-control @error('email') is-invalid @enderror" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="your@email.com"
                />
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <div class="input-group">
                <span class="input-group-text" style="background: #f8f9fa; border: 2px solid #e0e0e0;">
                    <i class="bi bi-lock" style="color: #FF8C42;"></i>
                </span>
                <input 
                    id="password" 
                    class="form-control @error('password') is-invalid @enderror"
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password"
                    placeholder="Enter your password"
                />
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        
        <div class="auth-links">
            <p style="margin: 0; color: #666; font-size: 0.9rem;">
                <a href="/">Go Back toHome</a>
                <button type="submit" class="btn btn-success" style="min-width: 100px;">
                    <i class="bi bi-box-arrow-in-right"></i> Login
                </button>
            </p>
        </div>
    </form>
</x-guest-layout>
