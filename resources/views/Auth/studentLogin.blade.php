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
             <a href="{{route('teacher.login')}}">   <button class="login-btn login">< Login as Teacher</button></a>
             <a href="{{route('homepage')}}">   <button class="register-btn signup">
                    < Back to Home</button></a>
            </div>
        </div>
    </nav>



    <div class="container">
        <div class="logo"></div>
        <h1>Log in as Student</h1>
        <p>Welcome back! Please enter your details.</p>
        <form action="{{ route('student.authenticate') }}" method="post">
            @csrf
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <div id="email-error" class="error"></div>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <button class="btn" type="submit">Sign in</button>
        </form>
    </div>

    <script>
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const emailError = document.getElementById('email-error');

        // Function to validate email
        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Add an event listener to the password field
        passwordInput.addEventListener('focus', () => {
            const emailValue = emailInput.value;

            // Check if the email is valid
            if (!validateEmail(emailValue)) {
                emailError.textContent = 'Please enter a valid email address.';
            } else {
                emailError.textContent = ''; // Clear error if valid
            }
        });
    </script>
</body>

</html>
