@extends('admin.layouts.auth')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h3 class="mb-3">Admin Login</h3>
        
        @if(session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Username or Email</label>
                <input name="name" type="text" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus placeholder="Enter your username / email" />
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 position-relative">
                <label class="form-label">Password</label>
                <input id="password" name="password" type="password" class="form-control pe-5 @error('password') is-invalid @enderror" required     />
                <i class="bi bi-eye position-absolute" id="togglePassword" style="right: 15px; top: 38px; cursor: pointer;" onclick="togglePassword()"></i>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3 d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        Remember Me
                    </label>
                </div>
                <a href="{{ route('admin.password.request') }}" class="text-decoration-none small">Forgot Your Password?</a>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">Login</button>
        </form>
    </div>
</div>
<script>
    function togglePassword() {
        var passwordField = document.getElementById("password");
        var toggleIcon = document.getElementById("togglePassword");
        
        if (passwordField.getAttribute("type") === "password") {
            passwordField.setAttribute("type", "text");
            toggleIcon.classList.remove("bi-eye");
            toggleIcon.classList.add("bi-eye-slash");
        } else {
            passwordField.setAttribute("type", "password");
            toggleIcon.classList.remove("bi-eye-slash");
            toggleIcon.classList.add("bi-eye");
        }
    }
</script>
@endsection
