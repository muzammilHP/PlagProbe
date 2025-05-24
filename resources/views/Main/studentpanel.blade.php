<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{asset('css/studentpanel.css')}}">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

<body>
    <div class="container">
        <div class="left">
            <div class="left-top">
               <div class="logo-head">
                    <img src="{{asset('images/Logo.png')}}" alt="PlagProbe Logo" id="logo-home-btn" style="cursor:pointer;">
                </div>
            </div>
            <div class="bottom">
                <div class="item" data-section="home">
                    <div class="item-left active">
                        <img width="25px" src="{{asset('images/home-icon.png')}}" alt="Home Logo">
                        <h4>Home</h4>
                    </div>
                </div>
                <div class="item" data-section="assignment-upload">
                    <div class="item-left">
                        <img width="25px" src="{{asset('images/upload-assignment.png')}}" alt="Assignment Upload Logo">
                        <h4>Upload Assignment</h4>
                    </div>
                    </a>
                </div>
                <div class="item" data-section="join-class">
                    <div class="item-left">
                        <img width="30px" src="{{asset('images/join-class.png')}}" alt="Join Class Logo">
                        <h4>Join Class</h4>
                    </div>
                    </a>
                </div>
                <div class="item" data-section="uploaded-history">
                    <div class="item-left">
                        <img width="30px" src="{{asset('images/assignment-history.png')}}" alt="Uploaded History Logo">
                        <h4>Uploaded History</h4>
                    </div>
                    </a>
                </div>

            </div>
        </div>
        <div class="right">
            <div class="right-top">
                <div class="left-r">
                    <h2>Hi {{ $student->username }} 👋 — Welcome to PlagProbe!</h2>
                </div>
                <div class="right-r" style="position: relative;">
        <div style="display: flex; flex-direction: column; align-items: flex-end;">
            <div class="profile" onclick="toggleProfileDropdown()" style="cursor: pointer;">
                <img src="{{ asset('images/profile.png') }}" alt="profile-pic">
                <span style="font-size:18px; font-weight:bold;">
                    {{ auth('student')->user()->username }}
                </span>
            </div>

            <!-- Dropdown -->
            <div id="profileDropdown" class="profile-dropdown" style="display: none; position: absolute; top: 60px; right: 0; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); width: 100px; padding: 10px; z-index: 1000; ">
                <a href="{{ route('student.profile') }}">Edit Profile</a>
                
                <form style="padding-left:1px;" action="{{ route('student.logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button  type="submit">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
            </div>
            <div class="bottom2">
                <div class="content-section" id="home">
                    <div class="first-section">
                        <div class="box">
                            <div class="within-box">
                                <p>Enrolled Classes</p>
                                <h2>{{$totalClasses}}</h2>
                            </div>
                            <img width="80px" src="{{asset('images/teacher-icon-01.svg')}}" alt="Class Icon">
                        </div>
                        <div class="box">
                            <div class="within-box">
                                <p>Total Assignments</p>
                                <h2>{{$totalAssignments}}</h4>
                            </div>
                            <img width="60px" src="{{asset('images/student-icon.svg')}}" alt="Student Icon">
                        </div>
                        <div class="box">
                            <div class="within-box">
                                <p>Uploaded Assignments</p>
                                <h2>{{$uploadedAssignments}}</h2>
                            </div>
                            <img width="50px" src="{{asset('images/assignment-logo.png')}}" alt="Assignment-Logo">
                        </div>
                        <div class="box">
                            <div class="within-box">
                                <p>Pending Assignments</p>
                                <h2>{{$pendingAssignments}}</h2>
                            </div>
                            <img width="50px" src="{{asset('images/report-icon.png')}}" alt="Report-Icon">
                        </div>
                    </div>
                    <div class="line"></div>
                    <div class="second-section">
                        <div class="student-motivation">
                            <div class="motivation-box">
                                <h2>🌟 Motivational Quote</h2>
                                <p>"Challenges are what make life interesting. Overcoming them is what makes life
                                    meaningful."
                                    <br><span>- Joshua J. Marine</span>
                                </p>
                            </div>

                            <div class="tips-box">
                                <h2>🎯 Student Success Tips</h2>
                                <ul>
                                    <li>📅 Plan your week every Sunday – organize your tasks and rest periods.</li>
                                    <li>⏰ Use the Pomodoro technique – 25 mins focus, 5 mins break to avoid burnout.
                                    </li>
                                    <li>🧠 Review your notes before bed – improves memory retention.</li>
                                    <li>✨ Reward yourself after completing tasks – it keeps motivation high.</li>
                                    <li>📖 Teach someone else – explaining a topic helps you understand it deeply.</li>
                                    <li>🔕 Turn off notifications while studying – protect your focus.</li>
                                    <li>🥤 Stay hydrated and take stretch breaks – fuels your brain and body.</li>
                                    <li>😴 Prioritize 7–8 hours of sleep – rest is part of productive learning.</li>
                                    <li>📚 Don’t hesitate to ask questions – learning thrives on curiosity.</li>
                                    <li>🎯 Set small, achievable daily goals – consistency beats cramming.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-section" id="assignment-upload" style="display: none;">
                    <div class="upload-assignments-top">
                        <h2>Upload Assignments</h2>
                    </div>
                    <div class="upload-assignments-bottom">

                    </div>
                </div>

                <div class="content-section" id="join-class" style="display: none;">
                    <div class="class-top">
                        <button id="joinClassBtn">Join New Class</button>
                    </div>

                    <!-- Join Class Modal -->
                    <div id="joinClassModel" class="joinClassModel">
                        <div class="model-content">
                            <span class="close" onclick="closeJoinClassModal()">&times;</span>
                            <h2>Enter Class Code</h2>
                            <form id="classForm">
                                @csrf
                                <input type="text" id="classCodeInput" name="classCode"
                                    placeholder="Enter Class Code"><br>
                                <button class="submit-btn join-class-btn" type="submit">Join Class</button>
                                <!-- <p id="joinClassMessage" style="color: red;"></p> -->
                            </form>
                        </div>
                    </div>


                    <div class="class-bottom">

                    </div>

                    <!--------- Class Details Section -------->
                    <div id="class-details-section" style="display: none;">
                        <div id="class-details-top" class="class-details-top">
                            <button id="backToClasses">
                                < Back to Classes</button>
                                    <div class="class-details-top-info">
                                        <h2 id="class-course-name"></h2>
                                        <h2 id="class-section-name"></h2>
                                        <!-- <h3 id="class-teacher-name"></h3> -->
                                    </div>
                                    <!-- <button id="createAssignmentBtn">Create Assignment</button> -->
                        </div>
                        <div id="class-details-bottom" class="class-details-bottom">

                            <div class="assignmentBox">

                            </div>
                        </div>
                    </div>

                </div>

                <div class="content-section" id="uploaded-history" style="display: none;">
                   <div class="uploaded-history-top">
                        <h2>Uploaded History</h2>
                    </div>
                    <div class="uploaded-history-bottom">

                    </div>
                </div>

                <!-- <h2>Upload Assignment for Plagiarism Check</h2>
                <div class="first">
                    <div class="box">
                        <div class="box-front">
                            <img src="{{asset('images/text assignment.jpg')}}" alt="Text assignment">
                            <h3>Text Assignment</h3>
                        </div>
                        <div class="box-back">
            
                            <button id="upload-btn" onclick="triggerTextFileInput()">Upload Assignment</button>
                            <input type="file" id="textfile-upload" name="file" accept=".pdf, .docx, .txt"
                                style="display:none;" onchange="handleText(event)">
                            <button onclick="fetchTextUploadedFiles()">Check Uploaded Files</button>
                            <button onclick="checkTextPlagiarism()">Check Plagiarism</button>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-front">
                            <img src="{{asset('images/handwritten.png')}}" alt="Handwritten assignment">
                            <h3>Handwritten</h3>
                        </div>
                        <div class="box-back">
                            
                            <button id="upload-btn" onclick="triggerHandFileInput()">Upload Assignment</button>
                            <input type="file" id="handfile-upload" name="files"
                                accept=".pdf, .docx, .txt, .jpg, .jpeg, .png" style="display:none;"
                                onchange="handleHandwritten(event)">
                            <button onclick="fetchHandUploadedFiles()">Check Uploaded Files</button>
                            <button onclick="checkHandPlagiarism()">Check Plagiarism</button>
                        </div>
                    </div>

                    <div class="box"></div>
                    <div class="box"></div>
                </div>

                <div id="plagiarism-report" style="margin-top: 20px;"></div>

                <div id="uploadedFilesModal" style="display: none;">
                    <div class="modal-content">
                        <span class="close-btn" onclick="closeModal()">&times;</span>
                        <h3>Uploaded Files</h3>
                        <ul id="file-list"></ul>
                    </div>
                </div>
                <div class="second">
                    <div class="stats"></div>
                    <div class="sales"></div>
                </div>
                <div class="third">
                    <div class="Top-Products">
                        <h1></h1>
                    </div>
                </div>  -->
            </div>
        </div>

        @if(Auth::guard('student')->check())
        <input type="hidden" id="student-id" value="{{ Auth::guard('student')->id() }}">
        @endif

    </div>
    <script>
        function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'flex' : 'none';
    }

    document.addEventListener('click', function(event) {
        const profile = document.querySelector('.profile');
        const dropdown = document.getElementById('profileDropdown');
        if (!profile.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });
    </script>
    <script src="{{asset('js/TextAssignment.js')}}"></script>
    <script src="{{asset('js/HandwrittenAssignment.js')}}"></script>
    <script src="{{asset('js/StudentPanel.js')}}"></script>
</body>

</html>