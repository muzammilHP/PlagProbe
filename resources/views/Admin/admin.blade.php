<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="{{asset('css/studentpanel.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
     <!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<style>
        /* General Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
            background-color: #f9f9f9;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table thead tr {
            background-color: #4CAF50;
            color: white;
            text-align: left;
            font-weight: bold;
        }

        table th,
        table td {
            padding: 12px 15px;
            border: 1px solid #ddd;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Action Buttons Styling */
        button {
            padding: 8px 12px;
            font-size: 14px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.edit-btn {
            background-color: #007BFF;
            color: white;
        }

        button.edit-btn:hover {
            background-color: #0056b3;
        }

        button.delete-btn {
            background-color: #DC3545;
            color: white;
        }

        button.delete-btn:hover {
            background-color: #a71d2a;
        }

        button.view-report-btn {
            background-color: #28A745;
            color: white;
        }

        button.view-report-btn:hover {
            background-color: #1e7e34;
        }

        /* Dashboard Section Styling */
        .dashboard-section {
            margin: 20px 0;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .dashboard-section h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .dashboard-section p {
            font-size: 16px;
            color: #555;
        }
    </style>
    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="left">
            <div class="left-top">
                <div class="logo-head">
                    <img src="{{asset('images/Logo.png')}}" alt="PlagProbe Logo">
                </div>
            </div>
            <div class="bottom">
                <div class="item" data-section="home">
                    <div class="item-left">
                        <img width="25px" src="{{asset('images/home-icon.png')}}" alt="Home Logo">
                        <h4>Dashboard</h4>
                    </div>
                </div>
                <div class="item" data-section="student-dashboard">
                    <div class="item-left">
                        <img width="25px" src="{{asset('images/upload-assignment.png')}}" alt="Assignment Upload Logo">
                        <h4>Student Dashboard</h4>
                    </div>
                    </a>
                </div>
                <div class="item" data-section="teacher-dashboard">
                    <div class="item-left">
                        <img width="30px" src="{{asset('images/join-class.png')}}" alt="Join Class Logo">
                        <h4>Teacher Dashboard</h4>
                    </div>
                    </a>
                </div>
                <div class="item" data-section="class-dashboard">
                    <div class="item-left">
                        <img width="30px" src="{{asset('images/assignment-history.png')}}" alt="Uploaded History Logo">
                        <h4>Class Dashboard</h4>
                    </div>
                    </a>
                </div>
                                <div class="item" data-section="report-dashboard">
                    <div class="item-left">
                        <img width="30px" src="{{asset('images/assignment-history.png')}}" alt="Uploaded History Logo">
                        <h4>Report Dashboard</h4>
                    </div>
                    </a>
                </div>

            </div>
        </div>
        <div class="right">
            <div class="right-top">
                <div class="left-r">
                    <h2>Hi 👋 — Welcome to Admin Panel!</h2>
                </div>
                <div class="right-r">
                    <a class="notification" href=""><img src="{{asset('images/notifications.png')}}"
                            alt="Notification"></a>
                    <a href=""
                        style="text-decoration: none; color: inherit; border:none;">
                        <div class="profile">
                            <img src="{{ asset('images/profile.png') }}" alt="profile-pic">
                            <span style="font-size:18px; font-weight:bold;"></span>
                        </div>
                    </a>
                    <form action="{{ route('admin.logout')}}" method="POST">
                        @csrf
                        <button type="submit">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
            <div class="bottom2">
                <div class="content-section" id="home">
                    <div class="first-section">
                        <div class="box">
                            <div class="within-box">
                                <p>Total Students</p>
                                <h2>{{$totalStudents}}</h2>
                            </div>
                            <img width="80px" src="{{asset('images/teacher-icon-01.svg')}}" alt="Class Icon">
                        </div>
                        <div class="box">
                            <div class="within-box">
                                <p>Total Teachers</p>
                                <h2>{{$totalTeachers}}</h4>
                            </div>
                            <img width="60px" src="{{asset('images/student-icon.svg')}}" alt="Student Icon">
                        </div>
                        <div class="box">
                            <div class="within-box">
                                <p>Total Classes</p>
                                <h2>{{$totalClasses}}</h2>
                            </div>
                            <img width="50px" src="{{asset('images/assignment-logo.png')}}" alt="Assignment-Logo">
                        </div>
                        <div class="box">
                            <div class="within-box">
                                <p>Total Reports</p>
                                <h2>{{$totalReports}}</h2>
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
            <h2>🛡️ Admin Insights</h2>
            <ul>
        <li>👥 View total number of students, teachers, classes, and reports from the admin dashboard.</li>
        <li>🧑‍💼 Manage student and teacher profiles efficiently with update and delete access.</li>
        <li>🏫 Create, edit, and organize class structures for seamless assignment handling.</li>
        <li>📊 Oversee and analyze plagiarism reports to ensure academic integrity.</li>
        <li>🛠️ Maintain system operations with regular backups and user activity monitoring.</li>
            </ul>
        </div>
                        </div>
                    </div>
                </div>
                <div class="content-section" id="student-dashboard" style="display: none;">
                    <h2>Registered Students</h2>
    <table id="studentsTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student->id }}</td>
                <td>{{ $student->username }}</td>
                <td>{{ $student->email }}</td>
                <td>{{ $student->contact }}</td>
                <td>
                    <button class="edit-btn" data-id="{{ $student->id }}">Edit</button>
                    <button class="delete-btn" data-id="{{ $student->id }}">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
                <div class="content-section" id="teacher-dashboard" style="display: none;">
                    <h2>Registered Teachers</h2>
    <table id="teachersTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Contact</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teachers as $teacher)
            <tr>
                <td>{{ $teacher->id }}</td>
                <td>{{ $teacher->username }}</td>
                <td>{{ $teacher->email }}</td>
                <td>{{ $teacher->contact }}</td>
                <td>
                    <button class="edit-teacher-btn" data-id="{{ $teacher->id }}">Edit</button>
                    <button class="delete-teacher-btn" data-id="{{ $teacher->id }}">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
                <div class="content-section" id="class-dashboard" style="display: none;">
                    <h2>Registered Classes</h2>
    <table id="classesTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Course Name</th>
                <th>Section</th>
                <th>Class Code</th>
                <th>Teacher</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($classes as $class)
            <tr>
                <td>{{ $class->id }}</td>
                <td>{{ $class->course_name }}</td>
                <td>{{ $class->section_name }}</td>
                <td>{{ $class->class_code }}</td>
                <td>{{ $class->teacher_name }}</td>
                <td>
                    <button class="edit-class-btn" data-id="{{ $class->id }}">Edit</button>
                    <button class="delete-class-btn" data-id="{{ $class->id }}">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
                <div class="content-section" id="report-dashboard" style="display: none;">
                    <h2>Generated Reports</h2>
    <table id="reportsTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Student Name</th>
                <th>Course</th>
                <th>Assignment</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reports as $report)
            <tr>
                <td>{{ $report->id }}</td>
                <td>{{ $report->student_name }}</td>
                <td>{{ $report->course_name }}</td>
                <td>{{ $report->assignment_id }}</td>
                <td>
                    <button class="view-report-btn" data-path="{{ $report->file_path }}">View</button>
                    <button class="delete-report-btn" data-id="{{ $report->id }}">Delete</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
        </div>

    </div>

    <script src="{{asset('js/AdminPanel.js')}}"></script>
</body>

</html>