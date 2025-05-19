<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Panel</title>
    <!-- <link rel="stylesheet" href="dashboard.css"> -->
    <link rel="stylesheet" href="{{asset('css/teacherpanel.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    </script>
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
                <div class="item active" data-section="home">
                    <div class="item-left">
                        <img width="25px" src="{{asset('images/home-icon.png')}}" alt="Home Logo">
                        <h4>Home</h4>
                    </div>
                </div>
                <div class="item" data-section="created-assignments">
                    <div class="item-left">
                        <img width="25px" src="{{asset('images/create-assignment.png')}}" alt="Create Assignment Logo">
                        <h4>Assignments History</h4>
                    </div>
                    </a>
                </div>
                <div class="item" data-section="class-management">
                    <div class="item-left">
                        <img width="25px" src="{{asset('images/classroom logo.svg')}}" alt="classroom logo">
                        <h4>Class Management</h4>
                    </div>
                    </a>
                </div>
                <div class="item" data-section="plagiarism-reports">
                    <div class="item-left">
                        <img width="30px" src="{{asset('images/report.png')}}" alt="Plag Reports">
                        <h4>Saved Reports</h4>
                    </div>
                    </a>
                </div>

            </div>
        </div>
        <div class="right">
            <div class="right-top">
    <div class="left-r">
        <h2>Hi {{ $teacher->username }} 👋 — Welcome to PlagProbe!</h2>
    </div>
    <div class="right-r" style="position: relative;">
        <div style="display: flex; flex-direction: column; align-items: flex-end;">
            <div class="profile" onclick="toggleProfileDropdown()" style="cursor: pointer;">
                <img src="{{ asset('images/profile.png') }}" alt="profile-pic">
                <span style="font-size:18px; font-weight:bold;">
                    {{ auth('teacher')->user()->username }}
                </span>
            </div>

            <!-- Dropdown -->
            <div id="profileDropdown" class="profile-dropdown" style="display: none; position: absolute; top: 60px; right: 0; background: white; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); width: 140px; padding: 10px; z-index: 1000; ">
                <a href="{{ route('teacher.profile') }}">Edit Profile</a>
                
                <form action="{{ route('teacher.logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit">
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
                                <p>Total Classes</p>
                                <h2>{{$totalClasses}}</h2>
                            </div>
                            <img width="80px" src="{{asset('images/teacher-icon-01.svg')}}" alt="Class Icon">
                        </div>
                        <div class="box">
                            <div class="within-box">
                                <p>Students Enrolled</p>
                                <h2>{{$totalStudents}}</h4>
                            </div>
                            <img width="60px" src="{{asset('images/student-icon.svg')}}" alt="Student Icon">
                        </div>
                        <div class="box">
                            <div class="within-box">
                                <p>Assignments Created</p>
                                <h2>{{$totalAssignments}}</h2>
                            </div>
                            <img width="50px" src="{{asset('images/assignment-logo.png')}}" alt="Assignment-Logo">
                        </div>
                        <div class="box">
                            <div class="within-box">
                                <p>Reports Generated</p>
                                <h2>{{$totalReports}}</h2>
                            </div>
                            <img width="50px" src="{{asset('images/report-icon.png')}}" alt="Report-Icon">
                        </div>
                    </div>
                    <div class="line"></div>
                    <div class="second-section">
                        <div class="second-one">
                            <div class="recent-activities">
                                <h3>Recent Activities</h3>
                                <ul>
                                    @foreach ($recentActivities as $activity)
                                    <li>
                                        <strong>{{ ucfirst(str_replace('_', ' ', $activity->activity_type)) }}</strong>:
                                        {{ $activity->description }}
                                        <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="second-two">
                            <div class="button-box">
                                <button id="createClassBtn-Home">Create New Class</button>
                            </div>

                            <div class="button-box">
                                <button id="viewReports">View Reports</button>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="content-section" id="created-assignments" style="display: none;">
                    <div class="created-assignments-top">
                        <h2>Recently Created Assignments</h2>
                    </div>
                    <div class="created-assignments-bottom">
                    </div>
                </div>
                <div class="content-section" id="class-management" style="display: none;">
                    <div class="class-top">
                        <button id="createClassBtn">Create New Class</button>
                    </div>

                    <div id="createClassModel" class="classModel">
                        <div class="model-content">
                            <span class="close">&times;</span>
                            <h2>Create a New Class</h2>
                            <form id="classForm">
                                @csrf

                                <label for="courseName">Course Name:</label><br>
                                <input type="text" placeholder="Enter Course Name Here!" name="courseName"
                                    id="courseName" required><br>

                                <label for="teacherName">Teacher Name:</label><br>
                                <input type="text" placeholder="Enter Teacher Name Here!" name="teacherName"
                                    id="teacherName" required><br>

                                <label for="section">Section:</label><br>
                                <input type="text" placeholder="Enter Section Here!" name="sectionName" id="section"
                                    required><br>

                                <button type="button" id="generateCode">Generate Class Code</button><br>

                                <input type="text" id="classCode" name="classCode" readonly required>

                                <button class="submit-btn" type="submit">Create Class</button>
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
                                    <button id="createAssignmentBtn">Create Assignment</button>
                        </div>
                        <div id="class-details-bottom" class="class-details-bottom">

                            <div class="assignmentBox">

                            </div>
                        </div>
                    </div>
                    <!-- Create Assignment Model -->
                    <div id="createAssignmentModel" class="assignmentModel" style="display:none" ;>
                        <div class="assignment-model-content">
                            <span class="assignmentModelClose close">&times;</span>
                            <form id="assignmentModelForm">
                                @csrf
                                <input type="hidden" id="class_code" name="class_code">
                                <label for="assignmentName">Assignment Name</label><br>
                                <input type="text" id="assignmentName" name="name"
                                    placeholder="Enter Assignment Name"><br>

                                <label for="completionDate">Completion Date</label><br>
                                <input type="date" id="completionDate" name="completion_date" required><br>

                                <label for="assignmentType">Assignment Type</label><br>
                                <select id="assignmentType" name="type" required>
                                    <option value="" disabled selected>Select Assignment Type</option>
                                    <option value="text">Text</option>
                                    <option value="handwritten">Handwritten</option>
                                    <option value="presentation">PPT</option>
                                    <option value="programming">Programming</option>
                                </select><br>

                                <button class="submit-btn create-assignment-btn" type="submit">Create
                                    Assignment</button>
                            </form>
                        </div>
                    </div>
                    <!-- End Create Assignment Model -->


                </div>
                <!--------- Assignment Details Section -------->
                <div id="assignment-details-section" style="display: none;">
                    <div id="assignment-details-top" class="assignment-details-top">
                        <button id="backToAssignment" class="backToAssignment-class">
                            < Back to Assignments</button>
                                <div class="assignment-details-top-info">
                                    <h2 id="assignment-name"></h2>
                                    <!-- <h3 id="class-teacher-name"></h3> -->
                                </div>

                                <form id="checkClassPlagForm" method="POST" target="_blank" style="display: none;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="id" id="classAssignmentId">
                                </form>

                                <button onclick="checkClassPlag()" id="createAssignmentBtn">Check Class
                                    Plag</button>
                    </div>
                    <div id="assignment-details-bottom" class="assignment-details-bottom">
                        <div class="assignmentBox">
                        </div>
                    </div>
                </div>
                <!-- End Assignment Details Section -->

                <!-- Check Plagiarism Modal -->
                <div id="check-plagiarism"
                    style="display: {{ isset($showModal) && $showModal ? 'block' : 'none' }}; border: 1px solid #ccc; padding: 20px; background: #fff; margin-top: 20px;">
                    <button onclick="document.getElementById('check-plagiarism').style.display='none'">&lt; Back to
                        Uploads</button>
                    <h2>Plagiarism Check Results</h2>

                    @if(isset($plagResults) && count($plagResults))
                    <table border="1" cellpadding="10" cellspacing="0" style="margin-top: 10px; width: 100%;">
                        <thead>
                            <tr>
                                <th>Compared With</th>
                                <th>Similarity</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($plagResults as $result)
                            <tr>
                                <td>{{ $result['compared_with'] }}</td>
                                <td>{{ $result['similarity'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p>No other assignments found to compare.</p>
                    @endif
                </div>

                <!-- End Check Plagiarism -->

                <!----------- End Class Details Section --------->

                <div class="content-section" id="plagiarism-reports" style="display: none;">
                    <div class="plagiarism-class-top">
                        <h2>Plagiarism Reports</h2>
                    </div>
                    @php
                    use App\Models\Report;
                    use Illuminate\Support\Facades\Auth;

                    $reports = Report::where('teacher_id', Auth::guard('teacher')->id())->get();
                    @endphp

                    @if($reports->count() > 0)
                    <div class="saved-reports">
                        @foreach($reports as $report)
                        <div class="saved-report">
                            <div class="report-info">
                                <div class="student-name">
                                <h3>{{ $report->student_name }}</h3>
                                <h3><span>{{ $report->student_email }}</span></h3>
                                </div>
                                <div class="course-info">
                                <h3>Course: <span>{{ $report->course_name }}</h3></span>
                                <h3>Section: <span>{{$report->section }}</h3></span>
                                </div>
                            </div>
                            <div class="report-actions">
                                <!-- View Icon -->
                                <a href="{{ route('view.report', ['path' => $report->file_path]) }}" target="_blank"
                                    title="View Report">
                                    <i class="fa fa-eye view-icon"
                                        style="font-size: 20px; color: green; cursor: pointer;"></i>
                                </a>

                                <!-- Delete Button -->
                                <form action="{{ route('delete.report', ['id' => $report->id]) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete Report"
                                        onclick="return confirm('Are you sure you want to delete this report?')">Delete</button>
                                </form>
                                <!--  -->
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p>No saved reports found.</p>
                    @endif
                </div>

            </div>
        </div>
    </div>
    <script>
        var viewIconUrl = "{{ asset('images/view-icon.png') }}";
        const csrfToken = "{{ csrf_token() }}";
    </script>

    <script src="{{asset('js/TextAssignment.js')}}"></script>
    <script src="{{asset('js/HandwrittenAssignment.js')}}"></script>
    <script src="{{asset('js/TeacherPanel.js')}}"></script>
    <script>
        // Logo click: go to Home section and highlight Home menu
        document.addEventListener('DOMContentLoaded', function() {
            var logoBtn = document.getElementById('logo-home-btn');
            if (logoBtn) {
                logoBtn.addEventListener('click', function() {
                    // Hide all content sections
                    document.querySelectorAll('.content-section').forEach(function(section) {
                        section.style.display = 'none';
                    });
                    // Show home section
                    var homeSection = document.getElementById('home');
                    if (homeSection) homeSection.style.display = 'block';
                    // Set Home menu item active
                    document.querySelectorAll('.item').forEach(function(item) {
                        item.classList.remove('active');
                        if (item.getAttribute('data-section') === 'home') {
                            item.classList.add('active');
                        }
                    });
                    // Save to localStorage for reloads (if used in TeacherPanel.js)
                    localStorage.setItem('activeSection', 'home');
                });
            }
        });

        
    function toggleProfileDropdown() {
        const dropdown = document.getElementById('profileDropdown');
        dropdown.style.display = (dropdown.style.display === 'none' || dropdown.style.display === '') ? 'block' : 'none';
    }

    document.addEventListener('click', function(event) {
        const profile = document.querySelector('.profile');
        const dropdown = document.getElementById('profileDropdown');
        if (!profile.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });
    </script>
</body>

</html>