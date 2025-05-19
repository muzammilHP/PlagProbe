<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>

<body style="background-image: url('{{ asset('images/login-back.avif') }}'); background-size: cover;">
    <div class="container">
    <nav>
            <div class="nav-container">
                <img src="{{asset('images/Logo.png')}}" alt="PlagProbe Logo">
                <ul>
                    <a href="{{route('homepage')}}"><li>Home</li></a>
                    <li>About Us</li>
                    <li>Products</li>
                    <a href=""><li>Our Team</li></a>
                    <a href=""><li>Contact Us</li></a>
                </ul>
                <div class="nav-right">
                    <a href="#"><button class="login">Login</button></a>
                    <a href="#" onclick="window.location.reload();"><button
                            class="signup">Register</button></a>
                </div>
            </div>
        </nav>

        <section id="content-section">
            
            <div class="right">

                <div class="signin">
                    <h1>Project is in Development Phase </h1>
                    <h1>We are trying our best to complete it as soon as possible</h1>
                
                    
                </div>
            </div>
        </section>
    </div>
</body>

</html>