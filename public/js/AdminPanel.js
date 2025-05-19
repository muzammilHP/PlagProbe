// document.addEventListener("DOMContentLoaded", function () {
//     const menuItems = document.querySelectorAll(".item");
//     const sections = document.querySelectorAll(".content-section");

//     menuItems.forEach(item => {
//         item.addEventListener("click", function () {
//             const sectionId = this.getAttribute("data-section");
//             const targetSection = document.getElementById(sectionId);

//             if (targetSection) {
//                 sections.forEach(section => {
//                     section.style.display = "none";
//                 });
//                 targetSection.style.display = "block";
//             }
//         });
//     });
// });

document.addEventListener("DOMContentLoaded", function () {
    const menuItems = document.querySelectorAll(".item");
    const sections = document.querySelectorAll(".content-section");

    menuItems.forEach(item => {
        item.addEventListener("click", function () {
            const sectionId = this.getAttribute("data-section");
            const targetSection = document.getElementById(sectionId);

            if (targetSection) {
                sections.forEach(section => {
                    section.style.display = "none";
                });
                targetSection.style.display = "block";
            }
        });
    });

    // Initialize DataTable
    $('#studentsTable').DataTable();
    $('#teachersTable').DataTable();
    $('#classesTable').DataTable();
    $('#reportsTable').DataTable();

    // Handle Edit Button
    $(document).on('click', '.edit-btn', function () {
        const studentId = $(this).data('id');
        alert('Edit functionality for student ID: ' + studentId);
        // Implement your edit logic here
    });

    // Handle Delete Button
    $(document).on('click', '.delete-btn', function () {
        const studentId = $(this).data('id');
        if (confirm('Are you sure you want to delete this student?')) {
            $.ajax({
                url: `/admin/students/${studentId}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    alert(response.message);
                    location.reload();
                },
                error: function (error) {
                    alert('Error deleting student.');
                }
            });
        }
    });

    // Handle Teacher Delete Button
$(document).on('click', '.delete-teacher-btn', function () {
    const teacherId = $(this).data('id');
    if (confirm('Are you sure you want to delete this teacher?')) {
        $.ajax({
            url: `/admin/teachers/${teacherId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                alert(response.message);
                location.reload();
            },
            error: function (error) {
                alert('Error deleting teacher.');
            }
        });
    }
});
// Handle Class Delete Button
$(document).on('click', '.delete-class-btn', function () {
    const classId = $(this).data('id');
    if (confirm('Are you sure you want to delete this class?')) {
        $.ajax({
            url: `/admin/classes/${classId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                alert(response.message);
                location.reload();
            },
            error: function (error) {
                alert('Error deleting class.');
            }
        });
    }
});

// Handle Report Delete Button
$(document).on('click', '.delete-report-btn', function () {
    const reportId = $(this).data('id');
    if (confirm('Are you sure you want to delete this report?')) {
        $.ajax({
            url: `/admin/reports/${reportId}`,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                alert(response.message);
                location.reload();
            },
            error: function (error) {
                alert('Error deleting report.');
            }
        });
    }
});

// Handle Report View Button
$(document).on('click', '.view-report-btn', function () {
    const filePath = $(this).data('path');
    window.open(`/admin/reports/view?path=${filePath}`, '_blank');
});
});