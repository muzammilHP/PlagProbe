<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Change</title>
    <link rel="stylesheet" href="{{ asset('css/pchange.css') }}">
</head>

<body>
    <nav>
        <div class="nav-container">
            <img src="{{ asset('images/Logo.png') }}" alt="PlagProbe Logo">

            <div class="nav-right">
                <a href="{{ $userType === 'student' ? route('studentpanel') : route('teacherpanel') }}">
                    <button class="signup">&lt; Back to {{ ucfirst($userType) }} Panel</button>
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1>Change Password</h1>
        <p>Update your password below.</p>
        <div class="box">
            <form action="{{ $userType === 'student' ? route('student.updatepassword') : route('teacher.updatepassword') }}"
            method="post" enctype="multipart/form-data">
                @csrf

                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" required>
                @error('newPassword')
                <div class="error">{{ $message }}</div>
                @enderror

                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" id="confirmPassword" name="newPassword_confirmation" required>
                @error('confirmPassword')
                <div class="error">{{ $message }}</div>
                @enderror

                <button class="btn" type="submit">Update Password</button>
                <a href="{{ $userType === 'student' ? route('student.profile') : route('teacher.profile') }}">
                    <button class="btn" type="button">Cancel</button>
                </a>
            </form>
        </div>
    </div>
</body>

</html>
