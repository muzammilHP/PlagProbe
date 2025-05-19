<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <link rel="stylesheet" href="{{asset('css/signup.css')}}">
    <style>
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>

<body>

    <nav>
        <div class="nav-container">
            <img src="{{asset('images/Logo.png')}}" alt="PlagProbe Logo">

            <div class="nav-right">
              <a href="{{route('student.signup')}}">  <button class="login-btn login">Signup as Student</button></a>
              <a href="{{route('homepage')}}">  <button class="register-btn signup">
                    < Back to Home</button></a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h1>Signup as Teacher</h1>
        <p>Create your account by filling in the details below.</p>
        <form action="{{ route('teacher.store') }}" method="POST">
            @csrf
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
            <div id="username-error" class="error"></div>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
            <div id="email-error" class="error"></div>

            <label for="contact">Contact No</label>
            <input type="text" id="contact" name="contact" placeholder="Enter your contact number" required>
            <div id="contact-error" class="error"></div>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <div id="password-error" class="error"></div>

            <label for="confirmPassword">Confirm Password</label>
            <input type="password" id="confirmPassword" name="password_confirmation" placeholder="Confirm your password" required>
            <div id="confirmPassword-error" class="error"></div>

            <button class="btn" type="submit">Sign up</button>
        </form>
        <a href="{{ route('teacher.login') }}">Already signed up? Log in</a>
    </div>

    <script>
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');
    const contactInput = document.getElementById('contact');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirmPassword');

    const usernameError = document.getElementById('username-error');
    const emailError = document.getElementById('email-error');
    const contactError = document.getElementById('contact-error');
    const passwordError = document.getElementById('password-error');
    const confirmPasswordError = document.getElementById('confirmPassword-error');

    function validateUsername(username) {
        const usernameRegex = /^[a-zA-Z0-9]+$/;
        return username.length >= 5 && usernameRegex.test(username) && /[a-zA-Z]/.test(username);
    }

    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function validateContact(contact) {
        const contactRegex = /^[0-9]{11}$/;
        return contactRegex.test(contact);
    }

    function validatePassword(password) {
        return password.length >= 8;
    }

    function validateConfirmPassword(password, confirmPassword) {
        return password === confirmPassword;
    }

    async function checkEmailExists(email) {
        try {
            const response = await fetch(`{{ route('check.teacher.email') }}?email=${encodeURIComponent(email)}`);
            if (!response.ok) throw new Error("Failed to check email");
            const data = await response.json();
            return data.exists;
        } catch (error) {
            console.error(error);
            return false; // Default to false if there's an error
        }
    }

    document.querySelectorAll('input').forEach((inputField) => {
        inputField.addEventListener('input', handleValidation);
        inputField.addEventListener('blur', handleValidation);
    });

    async function handleValidation(event) {
        const field = event.target.id;
        const value = event.target.value.trim();

        switch (field) {
            case 'username':
                if (!value) {
                    usernameError.textContent = 'Username is required.';
                }
                else if(value.length < 5){
                    usernameError.textContent = 'Username must be at least 5 characters Long';
                }
                else if (!validateUsername(value)) {
                    usernameError.textContent = 'Username must contain letters and numbers.';
                } else {
                    usernameError.textContent = '';
                }
                break;

            case 'email':
                if (!value) {
                    emailError.textContent = 'Email is required.';
                } else if (!validateEmail(value)) {
                    emailError.textContent = 'Please enter a valid email address.';
                } else if (await checkEmailExists(value)) {
                    emailError.textContent = 'Email already exists.';
                } else {
                    emailError.textContent = '';
                }
                break;

            case 'contact':
                if (!value) {
                    contactError.textContent = 'Contact number is required.';
                } else if (!validateContact(value)) {
                    contactError.textContent = 'Contact number must be exactly 11 digits.';
                } else {
                    contactError.textContent = '';
                }
                break;

            case 'password':
                if (!value) {
                    passwordError.textContent = 'Password is required.';
                } else if (!validatePassword(value)) {
                    passwordError.textContent = 'Password must be at least 8 characters long.';
                } else {
                    passwordError.textContent = '';
                }
                break;

            case 'confirmPassword':
                if (!value) {
                    confirmPasswordError.textContent = 'Confirm password is required.';
                } else if (!validateConfirmPassword(passwordInput.value, value)) {
                    confirmPasswordError.textContent = 'Passwords do not match.';
                } else {
                    confirmPasswordError.textContent = '';
                }
                break;
        }
    }
</script>
</body>

</html>
