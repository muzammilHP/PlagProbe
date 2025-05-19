function triggerUploadAssignment(button) {
let assignmentId = button.getAttribute("data-id");
    let fileInput = document.querySelector(`.file-upload[data-assignment-id='${assignmentId}']`);

    if (fileInput) {
        fileInput.click(); 
    }
}
document.addEventListener("DOMContentLoaded", function () {
    const menuItems = document.querySelectorAll(".item");
    const sections = document.querySelectorAll(".content-section");
    const classBottom = document.querySelector(".class-bottom");
    const classTop = document.querySelector(".class-top");
    const modelContent = document.querySelector(".model-content");
    const joinClassForm = document.getElementById("classForm");

    loadStudentClasses();
    fetchPendingAssignments();
    fetchUploadedAssignments();
    // fetchStudentAssignments();
    menuItems.forEach(item => {
    item.addEventListener("click", function () {
        const sectionId = this.getAttribute("data-section");

        sections.forEach(section => {
            section.style.display = "none";
        });

        document.getElementById(sectionId).style.display = "block";

        menuItems.forEach(i => i.querySelector(".item-left").classList.remove("active"));

        this.querySelector(".item-left").classList.add("active");
    });
});

    const joinClassBtn = document.getElementById("joinClassBtn");
    if (joinClassBtn) {
        joinClassBtn.addEventListener("click", function () {
            modelContent.style.display = "block";
            classTop.style.display = "none";
            classBottom.style.display = "none";
        });
    }

    const closeModal = document.querySelector(".close");
    if (closeModal) {
        closeModal.addEventListener("click", function () {
            modelContent.style.display = "none";
            classTop.style.display = "flex"; 
            classBottom.style.display = "flex";
        });
    }


    joinClassForm.addEventListener("submit", function (event) {
        event.preventDefault();
        
        classBottom.style.display = "flex";
        modelContent.style.display = "none";
        classTop.style.display = 'flex';

        let formData = new FormData(this);
        formData.forEach((value, key) => {
            console.log(key, value); 
        });

        fetch("http://127.0.0.1:8000/join-class", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Server returned an error: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            alert(data.message);
            loadStudentClasses();
            modelContent.style.display = "none";
            classTop.style.display = "flex";
            joinClassForm.reset();
        })
   });

    function loadStudentClasses() {
        fetch('http://127.0.0.1:8000/get-student-classes')
        .then(response => response.json())
        .then(classes => {

            classBottom.innerHTML = '';
            classes.forEach(classItem => {
                let newClassDiv = document.createElement("div");
                newClassDiv.classList.add("class");

                newClassDiv.innerHTML = `
                        <div class="class-div">
                        <div class="class-info">
                        <h3 class="course-name" data-class-code="${classItem.class_code}">${classItem.course_name}</h3>
                         </div>
                        <div class="class-info">
                        <h3 class="section-name">${classItem.section_name}</h3>
                         </div>
                        <div class="class-info">
                        <h4 class="teacher-name">${classItem.teacher_name}</h4>
                         </div>
                        <div class="class-info">
                        <p class="class-code">Class Code: ${classItem.class_code}</p>
                        </div>
                        <button class="delete-btn" data-id="${classItem.student_class_id}">Delete</button>
                        </div>
                `;
                
                classBottom.appendChild(newClassDiv);
                newClassDiv.addEventListener("click",function(){
                    classTop.style.display="none";
                    classBottom.style.display="none";
                    document.getElementById("class-details-section").style.display = "block";

                    let classCourseNameElement = document.getElementById("class-course-name");
                    classCourseNameElement.innerText = classItem.course_name;
                    classCourseNameElement.dataset.classCode = classItem.class_code;

                    fetchStudentAssignments();

                    // document.getElementById("class-course-name").innerText=classItem.course_name;
                    document.getElementById("class-section-name").innerText=classItem.section_name;
                    // document.getElementById("class-teacher-name").innerText=classItem.teacher_name;
                })
            });

            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function () {
                    deleteStudentClass(this.getAttribute("data-id"));
                });
            });

        });
        document.getElementById("backToClasses").addEventListener("click",function(){
            classTop.style.display="flex";
            classBottom.style.display="flex";
            document.getElementById("class-details-section").style.display = "none";
        });

    }

    function deleteStudentClass(classId) {
        fetch(`http://127.0.0.1:8000/delete-student-class/${classId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Class deleted successfully");
                loadStudentClasses();
            } else {
                alert("Error deleting class");
            }
        })
        .catch(error => {
            console.error("Error deleting class:", error);
        });
    }

    const classDetailsTop = document.getElementById("class-details-top");
    const classDetailsBottom = document.getElementById("class-details-bottom");
    
    function fetchStudentAssignments(){
        let selectedClassCode = document.getElementById("class-course-name").dataset.classCode;
        fetch(`http://127.0.0.1:8000/get-student-assignments/${selectedClassCode}`)
        .then(response => response.json())
        .then(assignments => {
            classDetailsBottom.innerHTML ='';

            assignments.forEach(assignmentItem =>{
                let newAssignmentDiv = document.createElement("div");
                newAssignmentDiv.classList.add("assignment");

                let buttonState = assignmentItem.is_submitted
                    ? `<button class="upload-btn submitted" disabled style="background-color:#28a745; cursor:not-allowed;">Submitted</button>`
                    : `<button class="upload-btn" onclick="triggerUploadAssignment(this)" data-id="${assignmentItem.id}">Upload</button>
                       <input type="file" class="file-upload" name="file" accept=".pdf, .docx, .txt"
                       style="display:none;" onchange="handleAssignment(event)" data-assignment-id="${assignmentItem.id}">`;

                newAssignmentDiv.innerHTML = `
                    <div class="assignment-div">
                    <div class="assignment-info-cover">
                    <div class="assignment-div-title">
                        <h3>${assignmentItem.name}</h3>
                    </div>
                    <div class="assignment-div-info">
                        <span>End Date:</span>
                        <h3>${assignmentItem.completion_date}</h3>
                    </div>

                    <div class="assignment-div-info">
                        <span>Assignment Type:</span>
                        <h3>${assignmentItem.type}</h3>
                    </div>
                    </div>

                    ${buttonState}
                    </div>
                `;

                classDetailsBottom.appendChild(newAssignmentDiv);

            })

            document.querySelectorAll(".delete-assignment-btn").forEach(button => {
                button.addEventListener("click", function () {
                    deleteAssignment(this.getAttribute("data-id"));
                });
            });
        })
        .catch(error => {
            console.error("Error fetching classes:", error);
        });
   }

});

function fetchPendingAssignments() {
    fetch("http://127.0.0.1:8000/get-pending-assignments/")
        .then(response => response.json())
        .then(pendingAssignments => {
            console.log("Pending Assignments:", pendingAssignments);
            const uploadAssignmentsBottom = document.querySelector(".upload-assignments-bottom");
            uploadAssignmentsBottom.innerHTML = "";

            if (!pendingAssignments.success || pendingAssignments.length === 0) {
                uploadAssignmentsBottom.innerHTML = "<p>No pending assignments 🎉</p>";
                uploadAssignmentsBottom.style.display = "block";
                return;
            }

            pendingAssignments.forEach(assignment => {
                const assignmentDiv = document.createElement("div");
                assignmentDiv.classList.add("assignment");

                assignmentDiv.innerHTML = `
                    <h3>${assignment.name}</h3>
                    <div class="assignment-info">
                        <span>Due Date:</span>
                        <h4>${assignment.completion_date}</h4>
                    </div>
                    <div class="assignment-info">
                        <span>Type:</span>
                        <h4>${assignment.type}</h4>
                    </div>
                    <button class="upload-btn" onclick="triggerUploadAssignment(this)" data-id="${assignment.id}">
                        Upload
                    </button>
                    <input type="file" class="file-upload" name="file" accept=".pdf, .docx, .txt"
                           style="display:none;" onchange="handleAssignment(event)" data-assignment-id="${assignment.id}">
                `;

                uploadAssignmentsBottom.appendChild(assignmentDiv);
            });
        })
        .catch(error => {
            console.error("Error fetching pending assignments:", error);
        });
}

function fetchUploadedAssignments() {
    fetch("http://127.0.0.1:8000/uploaded-history/")
        .then(response => response.json())
        .then(result => {
            const uploadedHistoryBottom = document.querySelector(".uploaded-history-bottom");
            uploadedHistoryBottom.innerHTML = "";

            if (!result.success || result.uploads.length === 0) {
                uploadedHistoryBottom.innerHTML = "<p>No uploaded assignments yet 📭</p>";
                uploadedHistoryDiv.style.display = "block";
                return;
            }

            result.uploads.forEach(upload => {
                let uploadDiv = document.createElement("div");
                uploadDiv.classList.add("assignment1");

                const uploadedDate = new Date(upload.created_at).toLocaleString();

                uploadDiv.innerHTML = `
                  <div class="assignment-uploaded-cover">
                    <div class="assignment-uploaded-title">
                    <h3>${upload.assignment_name}</h3>
                    </div>
                    <div class="assignment-uploaded-course">
                        <span>Course:</span>
                        <h4>${upload.course_name}</h4>
                    </div>

                    <div class="assignment-uploaded-section">
                        <span>Section:</span>
                        <h4>${upload.section_name}</h4>
                    </div>

                    <div class="assignment-uploaded-date">
                        <span>Submitted On:</span>
                        <h4>${uploadedDate}</h4>
                    </div>
                    </div>
                    <a href="/${upload.file_path}" target="_blank" class="view-btn">
                    <i class="fa fa-eye view-icon" style="font-size: 20px; color: green; cursor: pointer;" title="View Report"></i>
                    </a>

                    
                `;

                uploadedHistoryBottom.appendChild(uploadDiv);
            });

            // uploadedHistoryDiv.style.display = "block";
        })
        .catch(error => {
            console.error("Error fetching uploaded assignments:", error);
        });
}
function handleAssignment(event) {
    let fileInput = event.target;
    let assignmentId = fileInput.getAttribute("data-assignment-id"); 
    let file = fileInput.files[0];
    let studentId = document.getElementById("student-id").value;
    let uploadButton = document.querySelector(`.upload-btn[data-id='${assignmentId}']`);
 console.log("Student ID:", studentId);

 if (!file) return;

 let formData = new FormData();
 formData.append("assignment_id", assignmentId);
 formData.append("student_id",studentId);
 formData.append("file", file);

 fetch("http://127.0.0.1:8000/upload-assignment", {
     method: "POST",
     headers: {
         "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
     },
     body: formData,
 })
 .then(response => response.json())
 .then(data => {
    if (data.success) {
        alert("✅ " + data.message);
        if (uploadButton) {
                uploadButton.textContent = "Submitted";
                uploadButton.disabled = true;
                uploadButton.style.backgroundColor = "#28a745";
                uploadButton.style.cursor = "not-allowed";
        }
    } else {
        alert("❌ " + data.message);
    }
 })
 .catch(error => {
     console.error("Upload error:", error);
 });
}