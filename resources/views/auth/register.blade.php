@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h2 class="text-center mb-4">Create Account</h2>
                    
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" value="{{ old('username') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone_no" class="form-control" value="{{ old('phone_no') }}" required>
                        </div>
                        
                        <div class="mb-3 position-relative">
                            <label class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control pe-5" required>
                            <i class="bi bi-eye position-absolute" id="togglePassword" style="right: 15px; top: 38px; cursor: pointer;" onclick="togglePassword()"></i>
                        </div>
                        
                        <div class="mb-3 position-relative">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control pe-5" required>
                            <i class="bi bi-eye position-absolute" id="togglePasswordConfirm" style="right: 15px; top: 38px; cursor: pointer;" onclick="togglePasswordConfirm()"></i>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>
                        
                        <div class="text-center">
                            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
    
    function togglePasswordConfirm() {
        var passwordField = document.getElementById("password_confirmation");
        var toggleIcon = document.getElementById("togglePasswordConfirm");
        
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
