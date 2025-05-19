function triggerHandFileInput(){
    document.getElementById('handfile-upload').click();
}

function handleHandwritten(event) {
    const file = event.target.files[0];
    if (file) {
        // Fetch the current uploaded files
        fetch('http://127.0.0.1:8000/get-hand-uploaded-files', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const uploadedFilesCount = data.files.length;
                
                if (uploadedFilesCount >= 2) {
                    alert('You have already uploaded the maximum of 2 files. Delete one to upload a new file.');
                    return;  // Prevent file upload
                }

                const formData = new FormData();
                formData.append('file', file);

                fetch('http://127.0.0.1:8000/upload-handwritten-assignment', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            alert(data.error || 'Unknown error occurred');
                            throw new Error(data.error || 'Unknown error occurred');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                    } else {
                        alert('Failed to upload the assignment.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error uploading the assignment: ' + error.message);
                });
            } else {
                alert('Failed to fetch uploaded files');
            }
        })
        .catch(error => {
            console.error('Error fetching files:', error);
            alert('An error occurred while fetching files.');
        });
    }
}


function fetchHandUploadedFiles() {
    fetch('http://127.0.0.1:8000/get-hand-uploaded-files', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const fileList = document.getElementById('file-list');
            fileList.innerHTML = ''; // Clear previous files

            // Populate the list with files and add delete buttons
            data.files.forEach(file => {
                console.log(file);  // Log the file object to see the structure

                // Ensure file.id exists before calling deleteFile()
                if (file.id) {
                    const listItem = document.createElement('li');
                    listItem.innerHTML = `File: <strong>${file.file_name}</strong> | Uploaded At: ${file.created_at}
                        <button onclick="deleteHandFile(${file.id})" style="margin-left: 10px; color: white; background-color: red; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">
                            Delete
                        </button>
                    `;
                    fileList.appendChild(listItem);
                } else {
                    console.error('Missing file ID', file);  // Log an error if file.id is not present
                }
            });

            // Show the modal
            document.getElementById('uploadedFilesModal').style.display = 'block';
        } else {
            alert('Failed to fetch uploaded files');
        }
    })
    .catch(error => {
        console.error('Error fetching files:', error);
        alert('An error occurred while fetching files.');
    });
}

function closeModal() {
    document.getElementById('uploadedFilesModal').style.display = 'none';
}

function deleteHandFile(fileId) {
    if (!confirm('Are you sure you want to delete this file?')) {
        return;
    }

    fetch(`http://127.0.0.1:8000/delete-hand-file/${fileId}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            fetchHandUploadedFiles(); // Refresh the file list after deletion
        } else {
            alert('Failed to delete the file.');
        }
    })
    .catch(error => {
        console.error('Error deleting file:', error);
        alert('An error occurred while deleting the file.');
    });
}

function checkHandPlagiarism() {
    fetch('http://127.0.0.1:8000/get-hand-uploaded-files', {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.files.length === 2) {
            const fileIds = data.files.map(file => file.id);
            const fileNames = data.files.map(file => file.file_name); 
            const fileTypes = data.files.map(file => file.assignment_type);
            
            // Log the file IDs to debug
            // console.log('File IDs for plagiarism check:', fileIds);
            // console.log(fileIds[0]);
            // Check if both file IDs are valid
            if (!fileIds[0] || !fileIds[1]) {
                alert('Error: One or both file IDs are missing.');
                return;
            }

            // Send request to check plagiarism
            fetch('http://127.0.0.1:8000/check-hand-plagiarism', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    first_assignment_id: fileIds[0],
                    second_assignment_id: fileIds[1],
                }),
                
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    const similarity = result.similarity;
                    const message = result.message;
                    const feedback = result.feedback || "Review your assignments carefully to avoid plagiarism.";
                    const suggestions = result.suggestions || "Try paraphrasing, citing sources, and using original content.";

                    displayPlagiarismReport({
                        similarity,
                        message,
                        fileNames,
                        fileTypes,
                        feedback,
                        suggestions
                    });

                } else {
                    alert('Plagiarism check failed');
                }
            })
            .catch(error => {
                console.error('Error checking plagiarism:', error);
                alert('An error occurred while checking plagiarism.');
            });
        } else {
            alert('Please upload exactly two files before checking plagiarism.');
        }
    })
    .catch(error => {
        console.error('Error fetching uploaded files:', error);
        alert('An error occurred while fetching uploaded files.');
    });
}

function displayPlagiarismReport({ similarity, message, fileNames, fileTypes, feedback, suggestions }) {
    const reportContainer = document.getElementById('plagiarism-report');
    
    // Clear any existing content
    reportContainer.innerHTML = '';

    // Generate HTML for the report
    const reportHTML = `
        <div style="
            margin: 50px auto;
            padding: 20px;
            max-width: 600px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            background-color: #fff;
            font-family: Arial, sans-serif;
            text-align: center;
        ">
            <h2 style="color: #333; margin-bottom: 20px;">Plagiarism Report</h2>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <tr>
                    <th style="text-align: left; padding: 10px;">Assignment 1</th>
                    <td style="text-align: right; padding: 10px;">${fileNames[0]}</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 10px;">Assignment 2</th>
                    <td style="text-align: right; padding: 10px;">${fileNames[1]}</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 10px; ">Plagiarism Status</th>
                    <td style="text-align: right; padding: 10px;">${message}</td>
                </tr>
                <tr>
                    <th style="text-align: left; padding: 10px; ">Similarity</th>
                    <td style="text-align: right; padding: 10px;">${similarity}%</td>
                </tr>
            </table>
            <div style="margin-bottom: 20px; text-align: left;">
                <h3 style="margin-bottom: 10px; color: #444;">Feedback</h3>
                <p style="color: #666; line-height: 1.5;">${feedback}</p>
            </div>
            <div style="text-align: left;">
                <h3 style="margin-bottom: 10px; color: #444;">Suggestions</h3>
                <p style="color: #666; line-height: 1.5;">${suggestions}</p>
            </div>
        </div>
    `;

    reportContainer.innerHTML = reportHTML;

    reportContainer.scrollIntoView({ behavior: 'smooth' });
}



