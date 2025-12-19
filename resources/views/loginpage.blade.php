<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FABLAB - Login</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/loginpage.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container">
        <a href="{{ url('/') }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
            <span>Back</span>
        </a>
        <div class="left-side">
            <div class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="FABLAB Logo" class="logo-img">
                <p>Innovation • Creation • Collaboration</p>
            </div>
        </div>

        <div class="right-side">
            <div class="auth-tabs">
                <button class="tab-btn active" onclick="switchTab('login')">Login</button>
                <button class="tab-btn" onclick="switchTab('register')">Register</button>
            </div>

            <!-- Login Form -->
            <div id="login-form" class="form-section active">
                <form action="{{ route('google.login') }}">
                    @csrf
                    <button type="submit" class="google-btn">
                        <div class="google-icon"></div>
                        Continue with Google
                    </button>
                </form>

                <div class="divider">
                    <span>or login with email</span>
                </div>

                <form id="emailLoginForm" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="login-email">Email Address</label>
                        <input type="email" id="login-email" name="email" required value="{{ old('email') }}">
                        @error('email')
                            <span class="error" style="color: red; font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="login-password" name="password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('login-password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="error" style="color: red; font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- ✅ Keep reCAPTCHA (still commented out) -->
                    <!-- <div class="recaptcha-container">
                        <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                        @error('g-recaptcha-response')
                            <span class="error" style="color: red; font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div> -->

                    <button id="loginButton" type="submit" class="submit-btn">Sign In</button>

                    <!-- ✅ Added countdown area -->
                    <p id="countdownText" style="color:red; text-align:center; margin-top:10px; display:none;"></p>
                </form>

                <div class="forgot-password">
                 <a href="{{ route('forgot.password') }}">Forgot your password?</a>
                </div>
            </div>

            <!-- Register Form -->
            <div id="register-form" class="form-section">
                <form action="{{ route('google.login') }}">
                    @csrf
                    <button type="submit" class="google-btn">
                        <div class="google-icon"></div>
                        Sign Up with Google
                    </button>
                </form>

                <div class="divider">
                    <span>or create account</span>
                </div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="register-name">User Name</label>
                        <input type="text" id="register-name" name="name" required value="{{ old('name') }}">
                        @error('name')
                            <span class="error" style="color: red; font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="register-email">Email Address</label>
                        <input type="email" id="register-email" name="email" required value="{{ old('email') }}">
                        @error('email')
                            <span class="error" style="color: red; font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>

                     <div class="form-group">
                        <label for="register-phone">Phone Number</label>
                        <input type="text" id="register-phone" name="contact_number" required value="{{ old('contact_number') }}">
                        @error('contact_number')
                            <span class="error" style="color: red; font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="register-password">Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="register-password" name="password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('register-password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="error" style="color: red; font-size: 0.8rem;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="confirm-password">Confirm Password</label>
                        <div class="input-wrapper">
                            <input type="password" id="confirm-password" name="password_confirmation" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm-password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">Create Account</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            document.querySelectorAll('.form-section').forEach(section => section.classList.remove('active'));
            document.getElementById(tab + '-form').classList.add('active');
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = event.target.closest('.password-toggle').querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // ✅ Login attempt countdown
        const cooldownKey = "loginCooldown";
        const countdownText = document.getElementById("countdownText");
        const loginButton = document.getElementById("loginButton");

        function startCountdown(seconds) {
            countdownText.style.display = "block";
            loginButton.disabled = true;
            const interval = setInterval(() => {
                if (seconds <= 0) {
                    clearInterval(interval);
                    countdownText.style.display = "none";
                    loginButton.disabled = false;
                    localStorage.removeItem(cooldownKey);
                } else {
                    const mins = Math.floor(seconds / 60);
                    const secs = seconds % 60;
                    countdownText.textContent = `Too many attempts. Try again in ${mins}:${secs.toString().padStart(2, '0')} minutes.`;
                    seconds--;
                }
            }, 1000);
        }

        // Check saved cooldown on load
        const savedCooldown = localStorage.getItem(cooldownKey);
        if (savedCooldown) {
            const remaining = Math.floor((parseInt(savedCooldown) - Date.now()) / 1000);
            if (remaining > 0) startCountdown(remaining);
        }

        // If error message indicates cooldown, start it
        @if($errors->has('email') && Str::contains($errors->first('email'), 'Too many login attempts'))
            const cooldownDuration = 3 * 60; // 3 minutes
            localStorage.setItem(cooldownKey, Date.now() + cooldownDuration * 1000);
            startCountdown(cooldownDuration);
        @endif

        // SweetAlert setup
        const showToast = (icon, message) => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({ icon: icon, title: message });
        };

        @if(session('success'))
            showToast('success', '{{ session('success') }}');
        @endif

        @if(session('error'))
            showToast('error', '{{ session('error') }}');
        @endif

        @if($errors->any())
            showToast('error', '{{ $errors->first() }}');
        @endif
    </script>
</body>
</html>
