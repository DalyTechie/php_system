<!-- <?php
session_start();
require_once 'session_check.php';
require_once 'db.php'; // Include database connection
?> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'components/head.php'; ?>
    <style>
        /* Base styles for full width layout */
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: #f3f4f6;
            font-family: 'Khmer OS Siemreap', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
       

        /* Main container styles */
        .main-container {
            margin-left: 16rem;  /* Match sidebar width */
            min-height: 50vh;
            padding: 2rem;
            width: calc(100% - 16rem);  /* Full width minus sidebar */
            box-sizing: border-box;
            background-color: #f3f4f6;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        .search-input {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            width: 300px;
            font-size: 0.875rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #4338ca;
            transform: translateY(-1px);
        }

        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .inventory-table {
            width: 100%;
            border-collapse: collapse;
        }

        .inventory-table th,
        .inventory-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .inventory-table th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
        }

        .inventory-table tr:hover {
            background-color: #f9fafb;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-edit {
            background:  #2563eb;
            color: #2563eb;
            border: 1.5px solid #2563eb;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background 0.2s, color 0.2s;
        }

        .btn-edit:hover {
            background: #2563eb11;
            color: #174ea6;
        }

        .btn-delete {
            background:  #e74c3c;
            color: #e74c3c;
            border: 1.5px solid #e74c3c;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background 0.2s, color 0.2s;
        }

        .btn-delete:hover {
            background: #e74c3c11;
            color: #b91c1c;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            overflow-y: auto;
            padding: 2rem 1rem;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 600px;
            position: relative;
            margin: auto;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #111827;
            margin: 0;
        }

        .close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            margin: -0.5rem;
            border-radius: 0.375rem;
            transition: all 0.2s;
        }

        .close:hover {
            color: #111827;
            background-color: #f3f4f6;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 0.625rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 400;
            line-height: 1.5;
            color: #111827;
            background-color: #ffffff;
            background-clip: padding-box;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .form-control:focus {
            color: #111827;
            background-color: #ffffff;
            border-color: #4f46e5;
            outline: 0;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.25);
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .button-group {
            display: flex;
            gap: 0.5rem;
            margin-top: 1.5rem;
        }

        .btn-submit {
            background-color: #4f46e5;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-submit:hover {
            background-color: #4338ca;
        }

        .btn-cancel {
            background-color: #e2e8f0;
            color: #4a5568;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .btn-cancel:hover {
            background-color: #cbd5e0;
        }

        /* Course badge styling */
        .course-badge {
            background-color: #e0e7ff;
            color: #4338ca;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Responsive Adjustments */
        @media (max-width: 640px) {
            .modal-content {
                padding: 1.5rem;
            }

            .form-group {
                margin-bottom: 1rem;
            }

            .modal {
                padding: 1rem;
            }
        }
        .font-khmer h1{
            font-family: 'Khmer OS Muol Light', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        .header-title-btn-bg {
            background-color: #4f46e5;
            color: #fff;
            padding: 0.5rem 1.5rem;
            border-radius: 0.375rem;
            display: inline-block;
        }
     
    </style>
</head>
<body class="bg-gray-100">
    <?php include 'components/sidebar.php'; ?>
    <div class="ml-64">
        <?php include 'components/dashboard_stats.php'; ?>
    </div>
    
    <div class="main-container">
        <div class="page-header">
            <h1 class="text-2xl  font-khmer header-title-btn-bg">បន្ថែមដេប៉ាទីម៉ង</h1>
            <div class="header-actions">
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="text" placeholder="Search..." class="search-input" id="searchInput" style="padding-right: 2.5rem;">
                    <span style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background-color: #4f46e5; color: #fff; border-radius: 50%; width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; pointer-events: none; box-shadow: 0 1px 4px rgba(0,0,0,0.07);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                        </svg>
                    </span>
                </div>
                <button class="btn btn-primary" onclick="openModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 0a1 1 0 0 1 1 1v6h6a1 1 0 1 1 0 2H9v6a1 1 0 1 1-2 0V9H1a1 1 0 0 1 0-2h6V1a1 1 0 0 1 1-1z"/>
                    </svg>
                    បញ្ចូលដេប៉ាទីម៉ង
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="inventory-table">
                <thead>
                    <tr>
                        <th id="sortCourseId" style="cursor:pointer;">
                            លរ.<span id="courseIdArrow">▲▼</span>
                        </th>
                        <th>ដេប៉ាទីម៉ង</th>
                        <th>លេខកូដ.</th>
                        <th>ព័ត៌មានបន្ថែម</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch courses from database
                    $sql = "SELECT * FROM tblcourse ORDER BY course_id ASC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['course_id'] . "</td>
                                    <td>
                                        <div class='font-medium text-gray-900'>" . htmlspecialchars($row['course_name']) . "</div>
                                    </td>
                                    <td><span class='course-badge'>" . htmlspecialchars($row['course_code']) . "</span></td>
                                    <td>" . htmlspecialchars($row['description']) . "</td>
                                    <td>
                                        <div class='action-buttons'>
                                            <button class='btn-edit' onclick='editCourse(" . $row['course_id'] . ")'>
                                                <span style='font-size:1.1em;'>✏️</span> 
                                            </button>
                                            <button class='btn-delete' onclick='deleteCourse(" . $row['course_id'] . ")'>
                                                <span style='font-size:1.1em;'>🗑️</span> 
                                            </button>
                                        </div>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' class='text-center py-4'>No courses found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div id="addCourseModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">បញ្ចូលដេប៉ាទីម៉ង</h2>
                <button class="close" onclick="closeModal()">&times;</button>
            </div>
            
            <form id="addCourseForm">
                <div class="form-group">
                    <label for="course_name">ដេប៉ាទីម៉ង</label>
                    <input type="text" id="course_name" name="course_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="course_code">លេខកូដ</label>
                    <input type="text" id="course_code" name="course_code" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="description">ព័ត៌មានបន្ថែម</label>
                    <textarea id="description" name="description" class="form-control" required></textarea>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save mr-2"></i>រក្សាទុក
                    </button>
                    <button type="button" class="btn-cancel" onclick="closeModal()">
                        <i class="fas fa-times mr-2"></i>បោះបង់
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Course Modal -->
    <div id="editCourseModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">កែប្រែដេប៉ាទីម៉ង</h2>
                <button class="close" onclick="closeEditModal()">&times;</button>
            </div>
            
            <form id="editCourseForm">
                <input type="hidden" id="edit_course_id" name="course_id">
                
                <div class="form-group">
                    <label for="edit_course_name">ដេប៉ាទីម៉ង</label>
                    <input type="text" id="edit_course_name" name="course_name" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="edit_course_code">លេខកូដ</label>
                    <input type="text" id="edit_course_code" name="course_code" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="edit_description">ព័ត៌មានបន្ថែម</label>
                    <textarea id="edit_description" name="description" class="form-control" required></textarea>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save mr-2"></i>កែប្រែ
                    </button>
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">
                        <i class="fas fa-times mr-2"></i>បោះបង់
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- JavaScript for functionality -->
    <script>
        // Get the modals
        var addModal = document.getElementById("addCourseModal");
        var editModal = document.getElementById("editCourseModal");
        var addSpan = document.querySelector("#addCourseModal .close");
        var editSpan = document.querySelector("#editCourseModal .close");

        // Open add modal function
        function openModal() {
            addModal.style.display = "block";
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        // Prevent closing modal by clicking outside
        addModal.addEventListener('mousedown', function(e) {
            if (e.target === addModal) {
                e.stopPropagation();
            }
        });

        // Open edit modal function
        function editCourse(courseId) {
            // Fetch course data
            fetch('get_course.php?id=' + courseId)
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        // Fill the form with course data
                        document.getElementById('edit_course_id').value = data.course.course_id;
                        document.getElementById('edit_course_name').value = data.course.course_name;
                        document.getElementById('edit_course_code').value = data.course.course_code;
                        document.getElementById('edit_description').value = data.course.description;
                        
                        // Show the modal
                        editModal.style.display = "block";
                        document.body.style.overflow = 'hidden'; // Prevent background scrolling
                    } else {
                        alert('Error fetching course data: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error fetching course data. Please try again.');
                });
        }

        // Close modals functions
        function closeModal() {
            addModal.style.display = "none";
            document.body.style.overflow = ''; // Restore scrolling
        }

        function closeEditModal() {
            editModal.style.display = "none";
            document.body.style.overflow = ''; // Restore scrolling
        }

        // Close modals when clicking (x)
        addSpan.onclick = closeModal;
        editSpan.onclick = closeEditModal;

        // Handle add form submission
        document.getElementById('addCourseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('process_course.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Course added successfully!');
                    closeModal();
                    location.reload();
                } else {
                    alert('Error adding course: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error adding course. Please try again.');
            });
        });

        // Handle edit form submission
        document.getElementById('editCourseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'update');
            
            fetch('process_course.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('ការកែប្រែដេប៉ាទីម៉ងបានជោគជ័យ');
                    closeEditModal();
                    location.reload();
                } else {
                    alert('Error updating course: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('ការកែប្រែដេប៉ាទីម៉ងមិនបានជោគជ័យ');
            });
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let input = this.value.toLowerCase();
            let rows = document.querySelectorAll('.inventory-table tbody tr');

            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? '' : 'none';
            });
        });

        // Delete course function
        function deleteCourse(courseId) {
            if(confirm('Are you sure you want to delete this course?')) {
                const formData = new FormData();
                formData.append('course_id', courseId);
                
                fetch('delete_course.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert('ការលុបដេប៉ាទីម៉ងបានជោគជ័យ');
                        location.reload();
                    } else {
                        alert('Error deleting course: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('ការលុបដេប៉ាទីម៉ងមិនបានជោគជ័យ');
                });
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            let ascCourse = true;
            const th = document.getElementById('sortCourseId');
            const arrow = document.getElementById('courseIdArrow');
            th.addEventListener('click', function() {
                // Find the table body
                const tbody = document.querySelector('.inventory-table tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));
                rows.sort(function(a, b) {
                    // Course ID is in the first column (index 0)
                    let idA = parseInt(a.cells[0].innerText.trim(), 10);
                    let idB = parseInt(b.cells[0].innerText.trim(), 10);
                    return ascCourse ? idA - idB : idB - idA;
                });
                rows.forEach(row => tbody.appendChild(row));
                ascCourse = !ascCourse;
                arrow.textContent = ascCourse ? '▲▼' : '▼▲';
            });
        });

        // Edit course function
        function editCourse(courseId) {
            // Fetch course data and populate modal
            fetch(`get_course.php?id=${courseId}`)
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        document.getElementById('edit_course_id').value = data.course.course_id;
                        document.getElementById('edit_course_name').value = data.course.course_name;
                        document.getElementById('edit_course_code').value = data.course.course_code;
                        document.getElementById('edit_description').value = data.course.description;
                        document.getElementById('editCourseModal').style.display = 'flex';
                    } else {
                        alert('Error loading course data: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading course data. Please try again.');
                });
        }

        // Delete course function
        function deleteCourse(courseId) {
            if(confirm('តើអ្នកប្រាកដជាចង់លុបដេប៉ាទីម៉ងនេះមែនទេ?')) {
                fetch('process_course.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `action=delete&course_id=${courseId}`
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert('ការលុបដេប៉ាទីម៉ងបានជោគជ័យ');
                        location.reload();
                    } else {
                        alert('Error deleting course: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('ការលុបដេប៉ាទីម៉ងមិនបានជោគជ័យ');
                });
            }
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('.inventory-table tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if(text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
         <?php include 'components/top_bar.php'; ?>
</body>
</html>
    