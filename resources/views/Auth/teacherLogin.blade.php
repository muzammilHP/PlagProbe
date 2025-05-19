<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <style>
        .error {
            color: red;
            font-size: 0.9em;
        }
        .alert{
            border: 1px solid white;
            padding: 5px;
            color:white;
            border-radius: 5px;
            margin-left:40px;
        }
    </style>
</head>

<body>

    <nav>
        <div class="nav-container">
            <img src="{{asset('images/Logo.png')}}" alt="PlagProbe Logo">

            @if (session('error'))
            <div class="alert">
            {{ session('error') }}
            </div>
            @endif

            <div class="nav-right">
             <a href="{{route('student.login')}}">  <button class="login-btn login">< Login as Student</button></a>
             <a href="{{route('homepage')}}">   <button class="register-btn signup">
                    < Back to Home</button></a>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="logo"></div>
        <h1>Log in as Teacher</h1>
        <p>Welcome back! Please enter your details.</p>
        <form action="{{ route('teacher.authenticate') }}" method="post">
            @csrf
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <div id="email-error" class="error"></div>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <div id="password-error" class="error"></div>

            <button class="btn" type="submit">Sign in</button>
        </form>
    </div>

    <script>
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const emailError = document.getElementById('email-error');
        const passwordError = document.getElementById('password-error');

        // Validation functions
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Email regex
            return emailRegex.test(email);
        }

        function validatePassword(password) {
            return password.length >= 6; // Minimum 6 characters
        }

        // Add event listeners for real-time validation
        passwordInput.addEventListener('focus', () => {
            if (!validateEmail(emailInput.value)) {
                emailError.textContent = 'Please enter a valid email address.';
            } else {
                emailError.textContent = ''; // Clear the error
            }
        });

        passwordInput.addEventListener('blur', () => {
            if (!validatePassword(passwordInput.value)) {
                passwordError.textContent = 'Password must be at least 6 characters long.';
            } else {
                passwordError.textContent = ''; // Clear the error
            }
        });
    </script>
</body>

</html>
