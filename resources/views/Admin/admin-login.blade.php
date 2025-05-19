<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
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

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
    <h1>Admin Panel</h1>
            
            <div class="nav-right">
             <a href="{{route('homepage')}}">   <button class="register-btn signup">
                    < Back to Home</button></a>
            </div>
        </div>
    </nav>



    <div class="container">
        <div class="logo"></div>
        <h1>Log in as Admin</h1>
        <p>Welcome back! Please enter your details.</p>
        <form action="{{ route('admin.authenticate') }}" method="post">
    @csrf

    <label for="username">Username</label>
    <input type="text" id="username" name="username" placeholder="Enter your username" required>

    <label for="password">Password</label>
    <input type="password" id="password" name="password" placeholder="Enter your password" required>

    <button class="btn" type="submit">Sign in</button>
</form>

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
