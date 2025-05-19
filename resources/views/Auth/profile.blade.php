<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ ucfirst($userType) }} Profile</title>
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
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
            <img src="{{ asset('images/Logo.png') }}" alt="PlagProbe Logo">

            @if (session('success'))
            <div class="alert alert-success">
            {{ session('success') }}
            </div>
            @endif

            <div class="nav-right">
                <a href="{{ $userType === 'student' ? route('studentpanel') : route('teacherpanel') }}">
                    <button class="signup">&lt; Back to {{ ucfirst($userType) }} Panel</button>
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>{{ ucfirst($userType) }} Profile</h1>
        <p>Update your profile details below.</p>

        <div class="box">
            <form action="{{ $userType === 'student' ? route('student.updateprofile') : route('teacher.updateprofile') }}" 
                  method="post" enctype="multipart/form-data" id="profileForm">
                @csrf

                <!-- Display user image -->
                <img style="padding-left:120px; width:80px;" src="{{ asset('images/profile.png') }}" alt="Default Image"><br><br>

                <!-- Allow user to edit details -->
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="{{ $user->username }}">
                <div class="error" id="username-error"></div>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ $user->email }}">
                <div class="error" id="email-error"></div>

                <label for="contact">Contact No:</label>
                <input type="number" id="contact" name="contact" value="{{ $user->contact }}">
                <div class="error" id="contact-error"></div>

                <button type="submit" class="btn">Update Profile</button>
                <a href="{{ $userType === 'student' ? route('student.passwordchange') : route('teacher.passwordchange') }}">
                    <button class="btn" type="button">Change Password</button>
                </a>
            </form>
        </div>
    </div>

    <script>
        const usernameInput = document.getElementById('username');
        const emailInput = document.getElementById('email');
        const contactInput = document.getElementById('contact');

        const usernameError = document.getElementById('username-error');
        const emailError = document.getElementById('email-error');
        const contactError = document.getElementById('contact-error');

        function validateUsername(username) {
            return username.length >= 5;
        }

        function validateEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function validateContact(contact) {
            const contactRegex = /^[0-9]{11}$/;
            return contactRegex.test(contact);
        }

        document.getElementById('profileForm').addEventListener('submit', (e) => {
            let isValid = true;

            if (!validateUsername(usernameInput.value)) {
                usernameError.textContent = 'Username must be at least 5 characters.';
                isValid = false;
            }

            if (!validateEmail(emailInput.value)) {
                emailError.textContent = 'Please enter a valid email address.';
                isValid = false;
            }

            if (!validateContact(contactInput.value)) {
                contactError.textContent = 'Contact number must be exactly 11 digits.';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>

</html>
