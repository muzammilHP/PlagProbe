<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlagProbe</title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

</head>

<body>
    <div class="container">
        <nav>
            <div class="nav-container">
                <img src="{{asset('images/Logo.png')}}" alt="PlagProbe Logo">
                <ul>
                    <a href="#">
                        <li>Home</li>
                    </a>
                    <a href="#section-4">
                        <li>About Us</li>
                    </a>
                    <a href="#products">
                        <li>Products</li>
                    </a>
                    <a href="#section-5">
                        <li>Contact Us</li>
                    </a>
                </ul>
                <div class="nav-right">
                    <button class="login-btn login">Login</button>
                    <button class="register-btn signup">Register</button>

                </div>

            </div>
        </nav>

        <div id="login-modal" class="modal hidden">
            <div class="modal-content">
                <span class="close-btn" id="close-login">&times;</span>
                <h2>Login Options</h2>
                <div class="options">
                    <a href="{{route('student.login')}}"><button class="option-btn">Student Login</button></a>
                    <a href="{{route('teacher.login')}}"><button class="option-btn">Teacher Login</button></a>
                </div>
            </div>
        </div>

        <div id="register-modal" class="modal hidden">
            <div class="modal-content">
                <span class="close-btn" id="close-register">&times;</span>
                <h2>Register Options</h2>
                <div class="options">
                    <a href="{{route('student.signup')}}"><button class="option-btn">Student Register</button></a>
                    <a href="{{route('teacher.signup')}}"><button class="option-btn">Teacher Register</button></a>
                </div>
            </div>
        </div>

        <div id="carousel">
            <div class="carousel-container">
                <div id="section-2" class="carousel-item">
                    <div class="sec-2-cont">
                        <div class="left">
                            <h1>Your Partner in Academic Integrity</h1>
                            <p>Helping educators and students worldwide, PlagProbe offers cutting-edge solutions to
                                identify and reduce plagiarism in all types of assignments. Our platform ensures
                                comprehensive analysis, reliable reports, and secure processing to promote academic
                                integrity.</p>
                            <div class="buttons">
                                <button>Read More</button>
                                <img width="120px" src="{{asset('images/five_stars_icon.svg')}}" alt="Five Stars">
                            </div>
                        </div>
                        <div class="right">
                            <div class="img-deco">
                                <img src="{{asset('images/section-1.png')}}" alt="Section 1 Image">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div id="section-2">
            <div class="sec-2-cont">
                <div class="left">
                    <h1>All your essential Courses in one platform</h1>
                    <p style="position: relative; bottom: 10px;"> Whether you're looking to master graphic design, dive into freelancing, or learn the latest coding languages, we've got you covered. Our platform offers a vast collection of free courses designed to boost your skills and help you reach your goals. Start learning, creating, and succeeding today!</p>
                    <div style="display: flex; flex-direction: column; gap: 20px; margin-top: 30px;" class="buttons">
                        <button>Start Free Now</button>
                        <img width="120px" src="{{asset('images/five_stars_icon.svg')}}" alt="Five Stars">
                    </div>
                </div> -->

                <div class="line"></div>

                <div id="products">

                    <h2>Detecting Plagiarism Across All Formats</h2>


                    <div class="brief-products">
                        <h4>PlagProbe</h4>
                        <ul>
                            <a href="#">
                                <li class="item">Text Assignments</li>
                            </a>
                            <a href="#">
                                <li class="item">Handwritten Assignments</li>
                            </a>
                            <a href="#">
                                <li class="item">Presentations</li>
                            </a>
                            <a href="#">
                                <li class="item">Code Assignments</li>
                            </a>
                        </ul>
                    </div>

                    <div class="products-outer">
                        <div class="sect-products-cont">
                            <div class="right1">
                                <div class="top">
                                    <img class="halal" width="55px" src="{{asset('images/Ai-batch.jpg')}}" alt="Ai">
                                    <h2>Text Assignments</h2>
                                </div>
                                <p style="position:relative; bottom:9px;">
                                    The accuracy of our advanced plagiarism detection tool, PlagProbe, ensures academic
                                    integrity like never before. With cutting-edge algorithms, we analyze text
                                    assignments to identify copied content, providing detailed reports that are easy to
                                    understand. PlagProbe empowers educators, students, and professionals to maintain
                                    originality in their work.
                                </p>
                                <button class="readmore">Explore Now!</button>
                            </div>

                            <div class="left1">
                                <img src="{{asset('images/text.png')}}" alt="Text Assignments">
                            </div>
                        </div>
                    </div>

                    <div class="line2"></div>
                    <div class="line3"></div>

                    <div class="products-outer">
                        <div class="sect-products-cont">


                            <!-- Left Section: Product Image -->
                            <div class="left1">
                                <img style="border-left:none;" src="{{asset('images/handwritten.jpg')}}"
                                    alt="Handwritten Assignments">
                            </div>
                            <!-- Right Section: Product Info -->
                            <div class="right1">
                                <div class="top">
                                    <img class="halal" width="55px" src="{{asset('images/Ai-batch.jpg')}}" alt="Ai">
                                    <h2>Handwritten Assignments</h2>
                                </div>
                                <p style="position:relative; bottom:9px;">
                                    PlagProbe takes plagiarism detection to the next level by analyzing handwritten
                                    assignments with precision. Leveraging advanced OCR (Optical Character Recognition)
                                    and AI technologies, we ensure even manually written content is checked for
                                    originality. Preserve academic integrity effortlessly with accurate and reliable
                                    reports tailored for educators and students.
                                </p>
                                <button class="readmore">Explore Now!</button>
                            </div>
                        </div>

                    </div>

                    <div class="line2"></div>
                    <div class="line3"></div>

                    <div class="products-outer">
                        <div class="sect-products-cont">
                            <!-- Right Section: Product Info -->
                            <div class="right1">
                                <div class="top">
                                    <img class="halal" width="55px" src="{{asset('images/Ai-batch.jpg')}}" alt="Ai">
                                    <h2>Presentations</h2>
                                </div>
                                <p style="position:relative; bottom:9px;">
                                    Ensure originality in your presentations with PlagProbe's advanced plagiarism
                                    detection. Our tool scans slides, notes, and visual content to identify copied
                                    material, offering comprehensive reports that maintain academic and professional
                                    standards. Stay creative and plagiarism-free in every presentation you deliver.
                                </p>
                                <button class="readmore">Explore Now!</button>
                            </div>

                            <!-- Left Section: Product Image -->
                            <div class="left1">
                                <img src="{{asset('images/presentation.jpg')}}" alt=" Presentations">
                            </div>
                        </div>

                    </div>

                    <div class="line2"></div>
                    <div class="line3"></div>

                    <div class="products-outer">
                        <div class="sect-products-cont">


                            <!-- Left Section: Product Image -->
                            <div class="left1 last-left">
                                <img style="border-left:none;" src="{{asset('images/coding.jpg')}}"
                                    alt="code Assignments">
                            </div>
                            <!-- Right Section: Product Info -->
                            <div class="right1 last-service">
                                <div class="top">
                                    <img class="halal" width="55px" src="{{asset('images/Ai-batch.jpg')}}" alt="Ai">
                                    <h2>Code Assignments</h2>
                                </div>
                                <p style="position:relative; bottom:9px;">
                                    At Nora Al Arab, we take pride in offering premium-quality goat meat known for its
                                    rich
                                    flavor and tenderness. Our goat products are ethically sourced and carefully
                                    selected to
                                    meet the highest standards, bringing you a wholesome and delicious option for any
                                    meal.
                                    Discover the unique taste and quality that Nora Al Arab provides.
                                </p>
                                <button class="readmore">Explore Now!</button>
                            </div>
                        </div>


                    </div>

                    <div class="line2"></div>
                    <div class="line3"></div>

                </div>

                <div class="line"></div>
                <!-- Section 4 - -->
                <div id="section-4">
                    <div class="sect-4-cont">
                        <div class="top">
                            <h2>Why Choose Us?</h2>
                            <h4>3 Basic Steps to Succeed</h4>
                        </div>
                        <div class="steps">
                            <div class="step">
                                <img width="90px" src="{{asset('images/detection.avif')}}" alt="detection">
                                <h3 class="meat-icon">Plagiarism Detection</h3>
                                <p class="meat-icon">Our advanced system detects plagiarism across various assignment
                                    types, including text, handwritten work, programming code, and presentations. It
                                    provides detailed reports with highlighted similarities and actionable suggestions
                                    to improve originality, ensuring academic integrity.</p>
                            </div>
                            <div class="step">
                                <img width="80px" src="{{asset('images/learn.avif')}}" alt="learn">
                                <h3>Enhanced Learning Experience</h3>
                                <p>Students can refine their skills with our AI-powered tools, including tone detection
                                    for AI-generated content. Teachers can compare submissions, identify group
                                    plagiarism, and foster accountability in a collaborative environment.</p>
                            </div>
                            <div class="step">
                                <img width="80px" src="{{asset('images/management.avif')}}" alt="management">
                                <h3>Smart Management and Insights</h3>
                                <p>With features like student clustering, class creation, and assignment comparison, our
                                    platform empowers educators with actionable insights. Admin-level management ensures
                                    a seamless and secure experience for all users.</p>
                            </div>
                        </div>
                    </div>
                    <div class="users">
                        <div class="visits audience">
                            <h3>Visits</h3>
                            <h4>2000+</h4>
                        </div>
                        <div class="new-visiters  audience">
                            <h3>New Visitors</h3>
                            <h4>150+</h4>
                        </div>
                        <div class="avg-duration  audience">
                            <h3>Avg. Visit Duration</h3>
                            <h4>2 Mint+</h4>
                        </div>
                    </div>
                </div>

                <!-- Section 5 - Contact Us -->
                <div id="section-5">
                    <h1 style="text-align:center;">Get in Touch with Us</h1>
                    <div class="sect-5-cont">
                        <div class="left">
                            <p>We’re here to help! Whether you have a question about our tools, need assistance, or want
                                to provide
                                feedback, we’d love to hear from you.</p>
                            <div class="social d-flex justify-content-start">
                                <a href="https://www.instagram.com/muzammilnawaz124/"><img
                                        src="{{asset('images/insta.png')}}" alt="Insta"></a>
                                <a href="https://www.facebook.com/muzammil.nawaz.7587"><img
                                        src="{{asset('images/facebook.png')}}" alt="Facebook"></a>
                                <a href="https://x.com"><img src="{{asset('images/x.png')}}" alt="X"></a>
                            </div>
                            <p id="successMessage"
                                style="display:none; color:green; border: 2px solid black; padding: 10px; border-radius: 25px; width: 400px; text-align: center;">
                                Your Message has been sent successfully
                            </p>
                        </div>
                        <div class="right">
                            <img width="40px" src="{{asset('images/email.png')}}" alt="Email icon">
                            <h4>Send an Email</h4>
                            <p>info@PlagProbe.com</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div id="copyright">
                    &copy; Copyright 2025 | All Rights Reserved By PlagProbe
                </div>
            </div>

            <!-- JavaScript for Interactivity -->
            <script>
                // Carousel Control
                let currentIndex = 0;
                const slides = document.querySelectorAll('.carousel-item');
                const totalSlides = slides.length;

                function updateSlidePosition() {
                    document.querySelector('.carousel-container').style.transform = `translateX(-${currentIndex * 100}%)`;
                }

                function showNextSlide() {
                    currentIndex = (currentIndex + 1) % totalSlides;
                    updateSlidePosition();
                }

                function showPrevSlide() {
                    currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
                    updateSlidePosition();
                }

                // Auto Scroll Carousel
                setInterval(showNextSlide, 5000);


                <!-- JavaScript for Modals -->
                // Select elements
                const loginBtn = document.querySelector('.login-btn');
                const registerBtn = document.querySelector('.register-btn');
                const loginModal = document.getElementById('login-modal');
                const registerModal = document.getElementById('register-modal');
                const closeLoginBtn = document.getElementById('close-login');
                const closeRegisterBtn = document.getElementById('close-register');

                // Open login modal
                loginBtn.addEventListener('click', () => {
                    loginModal.classList.remove('hidden');
                });

                // Open register modal
                registerBtn.addEventListener('click', () => {
                    registerModal.classList.remove('hidden');
                });

                // Close login modal
                closeLoginBtn.addEventListener('click', () => {
                    loginModal.classList.add('hidden');
                });

                // Close register modal
                closeRegisterBtn.addEventListener('click', () => {
                    registerModal.classList.add('hidden');
                });

                // Close modals when clicking outside the modal content
                window.addEventListener('click', (e) => {
                    if (e.target === loginModal) {
                        loginModal.classList.add('hidden');
                    }
                    if (e.target === registerModal) {
                        registerModal.classList.add('hidden');
                    }
                });
            </script>
</body>

</html>