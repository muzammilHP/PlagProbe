document.addEventListener("DOMContentLoaded", function () {
    let currentView = "";
    const menuItems = document.querySelectorAll(".item");
    const sections = document.querySelectorAll(".content-section");
    const classBottom = document.querySelector(".class-bottom");
    const classTop = document.querySelector(".class-top");
    const modelContent = document.querySelector(".model-content");
    const createClassForm = document.getElementById("classForm");
    const homeSection = document.getElementById("home");
    const createAssignmentForm= document.getElementById("assignmentModelForm");
    const assignmentDetailsTop= document.getElementById("assignment-details-top");
    const assignmentDetailsBottom= document.getElementById("assignment-details-bottom");

    if (!classBottom || !classTop || !modelContent || !createClassForm) {
        console.error("One or more required elements are missing.");
        return;
    }

    fetchClasses();
    fetchAssignments();
    fetchAllTeacherAssignments();

    menuItems.forEach(item => {
        item.addEventListener("click", function () {
            const sectionId = this.getAttribute("data-section");
    
            // Hide all sections
            sections.forEach(section => {
                section.style.display = "none";
            });
    
            const targetSection = document.getElementById(sectionId);
            targetSection.style.display = "block";
    
            resetSection(sectionId);
    
            // Save to localStorage for future reloads
            localStorage.setItem("activeSection", sectionId);
    
            // Update active class
            menuItems.forEach(i => i.classList.remove("active"));
            this.classList.add("active");
        });
    });
    
    function resetSection(sectionId) {
        // Hide all content-sections first
        const allSections = document.querySelectorAll('.content-section');
        allSections.forEach(section => section.style.display = 'none');
    
        // Handle specific section reset logic
        switch (sectionId) {
            case "home":
                break;
    
            case "created-assignments":
                const assignmentDetailsSection = document.getElementById("assignment-details-section");
                if (assignmentDetailsSection) assignmentDetailsSection.style.display = "none";
    
                const assignmentDetailsBottom = document.getElementById("assignment-details-bottom");
                if (assignmentDetailsBottom) assignmentDetailsBottom.innerHTML = "";
    
                break;
    
                case "class-management":
                    const classDetailsSection = document.getElementById("class-details-section");
                    if (classDetailsSection) classDetailsSection.style.display = "none";
                
                    const createClassModel = document.getElementById("createClassModel");
                    if (createClassModel) createClassModel.style.display = "none";
                
                    const createAssignmentModel = document.getElementById("createAssignmentModel");
                    if (createAssignmentModel) createAssignmentModel.style.display = "none";
                
                    const classTop = document.querySelector(".class-top");
                    if (classTop) classTop.style.display = "flex";
                    
                    const classBottom = document.querySelector(".class-bottom");
                    if (classBottom) classBottom.style.display = "flex";
                
                    break;
    
                    case "plagiarism-reports":
                        const checkPlagiarismModal = document.getElementById("check-plagiarism");
                        if (checkPlagiarismModal) checkPlagiarismModal.style.display = "none";
                    
                        const assignmentDetailsSection2 = document.getElementById("assignment-details-section");
                        const assignmentDetailsBottom2 = document.getElementById("assignment-details-bottom");
                        if (assignmentDetailsSection2) assignmentDetailsSection2.style.display = "none";
                        if (assignmentDetailsBottom2) assignmentDetailsBottom2.innerHTML = "";
                    
                        const classDetailsSection2 = document.getElementById("class-details-section");
                        const createClassModel2 = document.getElementById("createClassModel");
                        const createAssignmentModel2 = document.getElementById("createAssignmentModel");
                        const classTop2 = document.querySelector(".class-top");
                        const classBottom2 = document.querySelector(".class-bottom");
                    
                        if (classDetailsSection2) classDetailsSection2.style.display = "none";
                        if (createClassModel2) createClassModel2.style.display = "none";
                        if (createAssignmentModel2) createAssignmentModel2.style.display = "none";
                        if (classTop2) classTop2.style.display = "none";
                        if (classBottom2) classBottom2.style.display = "none";
                        break;
        }
    
        // Show the selected section after resetting
        const selectedSection = document.getElementById(sectionId);
        if (selectedSection) selectedSection.style.display = "block";
    }
    
    
    function fetchClasses() {
        fetch("http://127.0.0.1:8000/get-classes")
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
                        <button class="delete-btn" data-id="${classItem.id}">Delete</button>
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
                        
                        fetchAssignments();

                        document.getElementById("class-section-name").innerText=classItem.section_name;
                        // document.getElementById("class-teacher-name").innerText=classItem.teacher_name;
                    })

                });

                // Attach delete event to buttons after elements are created
                document.querySelectorAll(".delete-btn").forEach(button => {
                    button.addEventListener("click", function () {
                        deleteClass(this.getAttribute("data-id"));
                    });
                });
            })
            .catch(error => {
                console.error("Error fetching classes:", error);
            });

            document.getElementById("backToClasses").addEventListener("click",function(){
                classTop.style.display="flex";
                classBottom.style.display="flex";
                document.getElementById("class-details-section").style.display = "none";
            });
    }

 
    function deleteClass(classId) {
        fetch(`http://127.0.0.1:8000/delete-class/${classId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Class deleted successfully");
                fetchClasses(); // Refresh the class list
            } else {
                alert("Error deleting class");
            }
        })
        .catch(error => {
            console.error("Error deleting class:", error);
        });
    }

    createClassForm.addEventListener("submit", function (event) {
        event.preventDefault();
        
        classBottom.style.display = "flex";

        let formData = new FormData(this);

        fetch("http://127.0.0.1:8000/create-class", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchClasses(); 

                modelContent.style.display = "none";
                classTop.style.display = "flex";
                createClassForm.reset();
            } else {
                alert("Error creating class.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    });


const createClassBtn = document.getElementById("createClassBtn");
const createClassBtnHome = document.getElementById("createClassBtn-Home");
const classManagementSection = document.getElementById("class-management");

function showCreateClassForm() {
    if (modelContent) modelContent.style.display = "block";
    if (classTop) classTop.style.display = "none";
    if (classBottom) classBottom.style.display = "none";
    const createClassModel = document.getElementById("createClassModel");
    if (createClassModel) createClassModel.style.display = "block";
}

if (createClassBtn) {
    createClassBtn.addEventListener("click", function () {
        showCreateClassForm();
    });
}

if (createClassBtnHome) {
    createClassBtnHome.addEventListener("click", function () {
        if (homeSection) homeSection.style.display = "none";
        if (classManagementSection) classManagementSection.style.display = "block";

        showCreateClassForm();
    });
}

const viewReports= document.getElementById("viewReports");
if(viewReports)
    viewReports.addEventListener("click",function(){
        homeSection.style.display = "none";
        document.getElementById("plagiarism-reports").style.display = "block";
    });

    const closeModal = document.querySelector(".close");
    if (closeModal) {
        closeModal.addEventListener("click", function () {
            modelContent.style.display = "none";
            classTop.style.display = "flex"; 
            classBottom.style.display = "flex";
        });
    }

    const generateCodeBtn = document.getElementById("generateCode");
    if (generateCodeBtn) {
        generateCodeBtn.addEventListener("click", function () {
            const classCodeInput = document.getElementById("classCode");
            if (classCodeInput) {
                classCodeInput.value = "CLS-" + Math.random().toString(36).slice(2, 8).toUpperCase();
            }
        });
    }

    const createAssignmentBtn = document.getElementById("createAssignmentBtn");
    const createAssignmentModel = document.getElementById("createAssignmentModel");
    const classDetailsTop = document.getElementById("class-details-top");
    const classDetailsBottom = document.getElementById("class-details-bottom");
    if(createAssignmentBtn){
        createAssignmentBtn.addEventListener("click",function(){
            let selectedClassCode = document.getElementById("class-course-name").dataset.classCode;
            if (!selectedClassCode) {
                alert("Error: Class code not found. Please select a class first.");
                return;
            }
            document.getElementById("class_code").value = selectedClassCode; 
            createAssignmentModel.style.display="block";
            classDetailsTop.style.display = "none";
            classDetailsBottom.style.display = "none";
        })
    }

    const closeAssignmentModel = document.querySelector(".assignmentModelClose");
    if (closeAssignmentModel) {
        closeAssignmentModel.addEventListener("click", function () {
            createAssignmentModel.style.display = "none";
            classDetailsTop.style.display = "flex"; 
            classDetailsBottom.style.display = "flex";
        });
    }

    createAssignmentForm.addEventListener("submit", function (event) {
        event.preventDefault();

        let formData = new FormData(this);
        let selectedClassCode = document.getElementById("class_code").value; // Get class code from hidden field
        console.log("Selected Class Code:", selectedClassCode); // Debugging line
        formData.append("class_code", selectedClassCode);
        formData.forEach((value, key) => {
            console.log(key + ": " + value);
        });
        fetch("http://127.0.0.1:8000/create-assignment", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                'Accept': 'application/json'
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
            fetchAssignments();
            createAssignmentModel.style.display = "none";
            classDetailsTop.style.display = "flex";
            classDetailsBottom.style.display = "flex";
            createAssignmentForm.reset();
        })
   });
    function fetchAssignments(){
        let selectedClassCode = document.getElementById("class-course-name").dataset.classCode;

        fetch(`http://127.0.0.1:8000/get-assignments/${selectedClassCode}`)
        .then(response => response.json())
        .then(assignments => {
            classDetailsBottom.innerHTML ='';

            assignments.forEach(assignmentItem =>{
                let newAssignmentDiv = document.createElement("div");
                newAssignmentDiv.classList.add("assignment");

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

                    <button class="delete-assignment-btn" data-id="${assignmentItem.id}">Delete</button>
                </div>
                `;
                newAssignmentDiv.addEventListener("click",function(){
                document.getElementById("class-details-section").style.display = "none";
                document.getElementById("assignment-details-section").style.display = "block";
                assignmentDetailsBottom.style.display="flex";

                fetchSubmittedAssignments(assignmentItem.id);

                document.getElementById("assignment-name").innerText=assignmentItem.name;

                document.getElementById("assignment-details-section").dataset.assignmentId = assignmentItem.id;
                document.getElementById("assignment-details-section").dataset.assignmentType = assignmentItem.type;
            });   

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

   function fetchAllTeacherAssignments() {
    fetch(`http://127.0.0.1:8000/get-teacher-assignments`)
        .then(response => response.json())
        .then(assignments => {
            const createdAssignmentsBottom = document.querySelector(".created-assignments-bottom");
            createdAssignmentsBottom.innerHTML = '';
            
            // assignments.forEach(assignmentItem => {
            //     let assignmentDiv1 = document.createElement("div");
            //     assignmentDiv1.classList.add("assignment1");

                // assignmentDiv1.innerHTML = `
                // <div class="assignment-div">
                //     <div class="assignment-info-cover">
                //     <div class="assignment-div-title">
                //         <h3>${assignmentItem.name}</h3>
                //     </div>
                //     <div class="assignment-div-info">
                //         <span>End Date:</span>
                //         <h3>${assignmentItem.completion_date}</h3>
                //     </div>

                //     <div class="assignment-div-info">
                //         <span>Assignment Type:</span>
                //         <h3>${assignmentItem.type}</h3>
                //     </div>
                //     </div>

                //     <button class="delete-assignment-btn" data-id="${assignmentItem.id}">Delete</button>
                // </div>
                // `;

                assignments.forEach(assignmentItem => {
                    let assignmentDiv1 = document.createElement("div");
                    assignmentDiv1.classList.add("assignment2");

                    assignmentDiv1.innerHTML = `
                <div class="assignment-div-c">
                    <div class="assignment-info-cover-c">
                    <div class="assignment-div-title-c">
                        <h3>${assignmentItem.assignment_name}</h3>
                    </div>

                    <div class="assignment-div-course-name">
            <p>Course</p>
            <h4>${assignmentItem.course_name}</h4>
        </div>

        <div class="assignment-div-section-name">
            <p>Section<p>
            <h4>${assignmentItem.section_name}</h4>
        </div>

                    <div class="assignment-div-completion-date">
                        <p>End Date<p>
                        <h4>${assignmentItem.completion_date}</h4>
                    </div>

                    <div class="assignment-div-type">
                        <p>Type</p>
                        <h4>${assignmentItem.type}</h4>
                    </div>

                    <div class="assignment-div-created-at">
                    <p>Created At</p>
                    <h4>${assignmentItem.created_at}</h4>
                    </div>

                    </div>

                    <button class="delete-assignment-btn" data-id="${assignmentItem.id}">Delete</button>
                </div>
                `;

                assignmentDiv1.addEventListener("click", function () {
                    document.getElementById("created-assignments").style.display = "none";
                    document.getElementById("assignment-details-section").style.display = "block";
                    assignmentDetailsBottom.style.display = "flex";

                    fetchAllSubmittedAssignments(assignmentItem.id);

                    document.getElementById("assignment-name").innerText = assignmentItem.assignment_name;
                    document.getElementById("assignment-details-section").dataset.assignmentId = assignmentItem.id;
                    document.getElementById("assignment-details-section").dataset.assignmentType = assignmentItem.type;
                });

                createdAssignmentsBottom.appendChild(assignmentDiv1);
            });

            document.querySelectorAll(".delete-assignment-btn").forEach(button => {
                button.addEventListener("click", function () {
                    deleteAssignment(this.getAttribute("data-id"));
                });
            });
        })
        .catch(error => {
            console.error("Error fetching teacher assignments:", error);
        });
}



   function deleteAssignment(assignmentId){
    fetch(`http://127.0.0.1:8000/delete-assignment/${assignmentId}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success){
            alert("Assignment Deleted Successfully");
            fetchAssignments();
        }
        else{
            alert("Error Deleting Assignment")
        }
    })
    .catch(error => {
        console.error("Error deleting class:", error);
    });
   }

   function fetchSubmittedAssignments(selectedAssignmentId) {
    currentView = "class-submitted-assignments";
    let selectedClassCode = document.getElementById("class-course-name").dataset.classCode;

    fetch(`http://127.0.0.1:8000/get-submitted-assignments/${selectedClassCode}/${selectedAssignmentId}`)
        .then(response => response.json())
        .then(assignments => {
            console.log("Fetched Data:", assignments);
            assignmentDetailsBottom.innerHTML = "";

            if (assignments.length === 0) {
                assignmentDetailsBottom.innerHTML = "<p>No assignments found.</p>";
                return;
            }

            assignments.forEach(submission => {
                let assignmentDiv = document.createElement("div");
                assignmentDiv.classList.add("assignment");

                let assignmentType = submission.assignment.type;
                console.log("Assignment Type"+assignmentType);
                
                let routeMap = {
                    text: "/textassignment/check-plag",
                    handwritten: "/handassignment/check-plag",
                    presentation: "/pptassignment/check-plag",
                    programming: "/codingassignment/check-plag"
                };

                let formAction = routeMap[assignmentType] || "#";

                assignmentDiv.innerHTML = `
                    <h3>${submission.student_name}</h3>
                    <h4>${submission.assignment.name}</h4>
                    <h4>Submitted on: <span>${new Date(submission.created_at).toLocaleString()}</span></h4>
                    <div class="view-class">
                        <form method="POST" action="${formAction}" target="_blank">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="id" value="${submission.id}">
                            <button type="submit" class="check-plag">Check Plag</button>
                        </form>
<a href="${submission.file_url}" target="_blank" class="view-btn">
    <i class="fa fa-eye view-icon" style="font-size: 20px; color: green; cursor: pointer;" title="View Report"></i>
</a>
                    </div>
                `;

                assignmentDetailsBottom.appendChild(assignmentDiv);
            });
        })
        .catch(error => {
            console.error("Error fetching submitted assignments:", error);
            classDetailsBottom.innerHTML = "<p>Error loading assignments.</p>";
        });

        document.getElementById("backToAssignment").addEventListener("click", function () {
            if (currentView === "class-submitted-assignments") {
                document.getElementById("class-details-section").style.display = "block";
                document.getElementById("assignment-details-section").style.display = "none";
            } else if (currentView === "all-submitted-assignments") {
                ocument.getElementById("created-assignments").style.display = "block";
            document.getElementById("assignment-details-section").style.display = "none";
            }
            currentView = "";
        });
}

function fetchAllSubmittedAssignments(selectedAssignmentId) {
    currentView = "all-submitted-assignments"; // Set the current view to all-submitted-assignments
    fetch(`http://127.0.0.1:8000/get-all-submitted-assignments/${selectedAssignmentId}`)
        .then(response => response.json())
        .then(assignments => {
            console.log("Fetched All Assignments:", assignments);
            assignmentDetailsBottom.innerHTML = "";

            if (assignments.length === 0) {
                assignmentDetailsBottom.innerHTML = "<p>No assignments found.</p>";
                return;
            }

            assignments.forEach(submission => {
                let assignmentDiv = document.createElement("div");
                assignmentDiv.classList.add("assignment");

                let assignmentType = submission.assignment?.type || "text"; // fallback for safety
                let assignmentName = submission.assignment?.name || "Unnamed";
                
                let routeMap = {
                    text: "/textassignment/check-plag",
                    handwritten: "/handassignment/check-plag",
                    presentation: "/pptassignment/check-plag",
                    programming: "/codingassignment/check-plag"
                };

                let formAction = routeMap[assignmentType] || "#";

                assignmentDiv.innerHTML = `
                    <h3>${submission.student_name}</h3>
                    <h4>${assignmentName}</h4>
                    <h4>Submitted on: <span>${new Date(submission.created_at).toLocaleString()}</span></h4>
                    <div class="view-class">
                        <form method="POST" action="${formAction}" target="_blank">
                            <input type="hidden" name="_token" value="${csrfToken}">
                            <input type="hidden" name="id" value="${submission.id}">
                            <button type="submit" class="check-plag">Check Plag</button>
                        </form>
                        <a href="${submission.file_url}" target="_blank" class="view-btn">
                <i class="fa fa-eye view-icon" style="font-size: 20px; color: green; cursor: pointer;" title="View Report"></i>
              </a>
                    </div>
                `;

                assignmentDetailsBottom.appendChild(assignmentDiv);
            });
        })
        .catch(error => {
            console.error("Error fetching all submitted assignments:", error);
            assignmentDetailsBottom.innerHTML = "<p>Error loading assignments.</p>";
        });

        document.getElementById("backToAssignment").addEventListener("click", function () {
            if (currentView === "class-submitted-assignments") {
                document.getElementById("class-details-section").style.display = "block";
                document.getElementById("assignment-details-section").style.display = "none";
            } else if (currentView === "all-submitted-assignments") {
                document.getElementById("created-assignments").style.display = "block";
            document.getElementById("assignment-details-section").style.display = "none";
            }
            currentView = "";
        });
}


document.getElementById("backToUploads").addEventListener("click",function(){
    document.getElementById("assignment-details-section").style.display = "block";
});

});

document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.delete-report');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function () {
            const index = this.getAttribute('data-index');
            
            fetch('http://127.0.0.1:8000/delete-report', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ index })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        });
    });
});


function checkClassPlag() {
    const assignmentSection = document.getElementById("assignment-details-section");
    const assignmentId = assignmentSection.dataset.assignmentId;
    const assignmentType = assignmentSection.dataset.assignmentType;

    const routeMap = {
        text: "/textassignment/class-plag",
        handwritten: "/handassignment/class-plag",
        presentation: "/textassignment/class-plag",
        programming: "/handassignment/class-plag"
    };

    const formAction = routeMap[assignmentType] || "#";
    const form = document.getElementById("checkClassPlagForm");

    document.getElementById("classAssignmentId").value = assignmentId;
    form.action = formAction;

    if (formAction !== "#") {
        form.submit();
    } else {
        alert("Invalid assignment type or route not defined.");
    }
}

function comingsoon(){
    document.getElementById("class-details-section").style.display = "none";
    document.getElementById("assignment-details-section").style.display = "none";
    document.getElementById("coming-soon-section").style.display = "flex";


}

/*-------------------------------Creating Class Form Checks------------------*/
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("classForm");

    const courseNameInput = document.getElementById("courseName");
    const teacherNameInput = document.getElementById("teacherName");
    const sectionInput = document.getElementById("section");
    const classCodeInput = document.getElementById("classCode");
    const generateBtn = document.getElementById("generateCode");

    function createErrorElement(input) {
        let error = document.createElement("div");
        error.className = "error-message";
        error.style.color = "red";
        error.style.fontSize = "14px";
        error.style.marginTop = "4px";
        error.style.display = "none";
        input.insertAdjacentElement('afterend', error);
        return error;
    }

    const courseError = createErrorElement(courseNameInput);
    const teacherError = createErrorElement(teacherNameInput);
    const sectionError = createErrorElement(sectionInput);
    const codeError = createErrorElement(classCodeInput);

    const alphanumericRegex = /^[a-zA-Z0-9\s]+$/;
    const alphabetOnlyRegex = /^[a-zA-Z\s]+$/;
    const sectionRegex = /^[A-Z]{3}-\d{1,2}[A-Z]?$/; 

    function validateCourseName() {
        const value = courseNameInput.value.trim();
        if (!value) {
            courseError.textContent = "Course name is required.";
            courseError.style.display = "block";
            return false;
        } else if (!alphanumericRegex.test(value) || value.length < 3 || value.length > 50) {
            courseError.textContent = "3–50 letters/numbers only.";
            courseError.style.display = "block";
            return false;
        } else {
            courseError.textContent = "";
            courseError.style.display = "none";
            return true;
        }
    }

    function validateTeacherName() {
        const value = teacherNameInput.value.trim();
        if (!value) {
            teacherError.textContent = "Teacher name is required.";
            teacherError.style.display = "block";
            return false;
        } else if (!alphabetOnlyRegex.test(value) || value.length < 3 || value.length > 50) {
            teacherError.textContent = "3–50 alphabetic characters only.";
            teacherError.style.display = "block";
            return false;
        } else {
            teacherError.textContent = "";
            teacherError.style.display = "none";
            return true;
        }
    }

    function validateSection() {
        const value = sectionInput.value.trim();
        if (!value) {
            sectionError.textContent = "Section is required.";
            sectionError.style.display = "block";
            return false;
        } else if (!sectionRegex.test(value)) {
            sectionError.textContent = "Format must be like BSE-7B.";
            sectionError.style.display = "block";
            return false;
        } else {
            sectionError.textContent = "";
            sectionError.style.display = "none";
            return true;
        }
    }

    function validateClassCode() {
        const value = classCodeInput.value.trim();
        if (!value) {
            codeError.textContent = "Generate a class code.";
            codeError.style.display = "block";
            return false;
        } else {
            codeError.textContent = "";
            codeError.style.display = "none";
            return true;
        }
    }

    courseNameInput.addEventListener("blur", validateCourseName);
    teacherNameInput.addEventListener("blur", validateTeacherName);
    sectionInput.addEventListener("blur", validateSection);
    classCodeInput.addEventListener("blur", validateClassCode);


    form.addEventListener("submit", function (e) {
        const isValid =
            validateCourseName() &&
            validateTeacherName() &&
            validateSection() &&
            validateClassCode();

        if (!isValid) {
            e.preventDefault();
        }
    });
});
/*-------------------------------Creating Assignment Form Checks------------------*/
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("assignmentModelForm");

    const assignmentNameInput = document.getElementById("assignmentName");
    const completionDateInput = document.getElementById("completionDate");
    const assignmentTypeInput = document.getElementById("assignmentType");

    function createErrorElement(input) {
        let error = document.createElement("div");
        error.className = "error-message";
        error.style.color = "red";
        error.style.fontSize = "14px";
        error.style.marginTop = "4px";
        error.style.display = "none";
        input.insertAdjacentElement('afterend', error);
        return error;
    }

    const assignmentNameError = createErrorElement(assignmentNameInput);
    const completionDateError = createErrorElement(completionDateInput);
    const assignmentTypeError = createErrorElement(assignmentTypeInput);

    // Validation Functions
    function validateAssignmentName() {
        const value = assignmentNameInput.value.trim();
        if (!value) {
            assignmentNameError.textContent = "Assignment name is required.";
            assignmentNameError.style.display = "block";
            return false;
        } else {
            assignmentNameError.textContent = "";
            assignmentNameError.style.display = "none";
            return true;
        }
    }

    function validateCompletionDate() {
        const today = new Date().toISOString().split('T')[0];
        const value = completionDateInput.value;
        if (!value) {
            completionDateError.textContent = "Completion date is required.";
            completionDateError.style.display = "block";
            return false;
        } else if (value < today) {
            completionDateError.textContent = "Completion date must be today or in the future.";
            completionDateError.style.display = "block";
            return false;
        } else {
            completionDateError.textContent = "";
            completionDateError.style.display = "none";
            return true;
        }
    }

    function validateAssignmentType() {
        const value = assignmentTypeInput.value;
        if (!value) {
            assignmentTypeError.textContent = "Assignment type is required.";
            assignmentTypeError.style.display = "block";
            return false;
        } else {
            assignmentTypeError.textContent = "";
            assignmentTypeError.style.display = "none";
            return true;
        }
    }

    // Adding blur event listeners to validate the fields
    assignmentNameInput.addEventListener("blur", validateAssignmentName);
    completionDateInput.addEventListener("blur", validateCompletionDate);
    assignmentTypeInput.addEventListener("blur", validateAssignmentType);

    // Show error message when focus moves to the next field
    const fields = [assignmentNameInput, completionDateInput, assignmentTypeInput];
    
    fields.forEach(field => {
        field.addEventListener("focus", function() {
            const previousField = this.previousElementSibling;
            if (previousField && previousField.tagName === 'LABEL') {
                previousField.style.color = 'red';
            }
        });

        field.addEventListener("blur", function() {
            const previousField = this.previousElementSibling;
            if (previousField && previousField.tagName === 'LABEL') {
                previousField.style.color = '';
            }
        });
    });

    // Form submission validation
    form.addEventListener("submit", function (e) {
        const isValid = validateAssignmentName() && validateCompletionDate() && validateAssignmentType();

        if (!isValid) {
            e.preventDefault();
        }
    });
});





