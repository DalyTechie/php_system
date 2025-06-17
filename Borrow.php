<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once './session_check.php';
    include './components/head.php';
    ?>
    <title>Borrow Books - Library Management System</title>
    <style>
        .main-container {
            margin-left: 16rem;
            padding: 2rem;
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
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            width: 300px;
        }

        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            margin-bottom: 2rem;
        }

        .books-table {
            width: 100%;
            border-collapse: collapse;
        }

        .books-table th,
        .books-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .books-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            font-weight: 500;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
        }

        .btn-success {
            background-color: #22c55e;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 500px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .close-btn {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-btn:hover {
            color: black;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .status-available {
            color: #22c55e;
            font-weight: 500;
        }

        .status-borrowed {
            color: #ef4444;
            font-weight: 500;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <?php 
    include './components/sidebar.php';

    ?>
    
    <div class="main-container">
  

        <div class="page-header">
            <h1>Borrow Books</h1>
            <div class="header-actions">
                <input type="text" id="searchInput" placeholder="Search books..." class="search-input" onkeyup="searchBooks()">
                <button type="button" class="btn btn-primary" onclick="openNewBorrowModal()">
                    <i class="fas fa-plus"></i> Add New Borrow
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="books-table">
                <thead>
                    <tr>
                        <th>BorrowerID</th>
                        <th>Student Name</th>
                        <th>Title</th>
                        <th>Course</th>
                        <th>Borrow Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require("db.php");
                    
                    // First try to get borrowed books
                    $sql = "SELECT b.borrow_id, b.student_id, 
                            CONCAT(s.firstname, ' ', s.lastname) as student_name,
                            bk.title as book_title,
                            c.course_name,
                            b.borrow_date,
                            b.return_date,
                            b.status
                            FROM tblborrower b
                            INNER JOIN tblstudent s ON b.student_id = s.student_id
                            INNER JOIN tblbooks bk ON b.book_id = bk.book_id
                            LEFT JOIN tblcourse c ON s.course_id = c.course_id
                            WHERE b.status = 'borrowed'
                            ORDER BY b.borrow_date DESC";

                    $result = $conn->query($sql);
                    
                    if (!$result) {
                        echo "<tr><td colspan='8' class='text-center text-danger'>Error executing query: " . $conn->error . "</td></tr>";
                    } else if ($result->num_rows === 0) {
                        // If no borrowed books found, show available books
                        $available_sql = "SELECT 
                                        bk.book_id,
                                        bk.title as book_title,
                                        bk.author,
                                        bk.category
                                        FROM tblbooks bk
                                        WHERE bk.book_id NOT IN (
                                            SELECT book_id 
                                            FROM tblborrower 
                                            WHERE status = 'borrowed'
                                        )
                                        ORDER BY bk.title";
                        
                        $available_result = $conn->query($available_sql);
                        
                        if (!$available_result) {
                            echo "<tr><td colspan='8' class='text-center text-danger'>Error executing query: " . $conn->error . "</td></tr>";
                        } else if ($available_result->num_rows === 0) {
                            echo "<tr><td colspan='8' class='text-center text-warning'>No books available in the database</td></tr>";
                        } else {
                            while($row = $available_result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>-</td>";
                                echo "<td>-</td>";
                                echo "<td>" . htmlspecialchars($row['book_title']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['author'] ?? 'N/A') . "</td>";
                                echo "<td>-</td>";
                                echo "<td>-</td>";
                                echo "<td class='status-available'>Available</td>";
                                echo "<td class='actions'>";
                                echo "<button onclick='openBorrowModal(\"" . htmlspecialchars($row['book_id']) . "\")' 
                                        class='btn btn-primary btn-sm'>
                                        <i class='fas fa-book'></i> Borrow
                                      </button>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        }
                    } else {
                        $counter = 1;
                        while($row = $result->fetch_assoc()) {
                            $statusClass = $row['status'] === 'returned' ? 'status-available' : 'status-borrowed';
                            echo "<tr>";
                            echo "<td>" . $counter . "</td>";
                            echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['book_title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['course_name'] ?? 'N/A') . "</td>";
                            echo "<td>" . date('M d, Y', strtotime($row['borrow_date'])) . "</td>";
                            echo "<td>" . ($row['return_date'] ? date('M d, Y', strtotime($row['return_date'])) : 'N/A') . "</td>";
                            echo "<td class='" . $statusClass . "'>" . ucfirst($row['status']) . "</td>";
                            echo "<td class='actions'>";
                            if ($row['status'] === 'borrowed') {
                                echo "<button onclick='viewBorrow(\"" . htmlspecialchars($row['borrow_id']) . "\")' 
                                        class='btn btn-info btn-sm'>
                                        <i class='fas fa-eye'></i> View
                                      </button>";
                                echo "<button onclick='updateBorrow(\"" . htmlspecialchars($row['borrow_id']) . "\")' 
                                        class='btn btn-primary btn-sm'>
                                        <i class='fas fa-edit'></i> Edit
                                      </button>";
                                echo "<button onclick='deleteBorrow(\"" . htmlspecialchars($row['borrow_id']) . "\")' 
                                        class='btn btn-danger btn-sm'>
                                        <i class='fas fa-trash'></i> Delete
                                      </button>";
                                echo "<button onclick='returnBook(\"" . htmlspecialchars($row['borrow_id']) . "\")' 
                                        class='btn btn-success btn-sm'>
                                        <i class='fas fa-book'></i> Return
                                      </button>";
                            }
                            echo "</td>";
                            echo "</tr>";
                            $counter++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Borrow Book Modal -->
    <div id="borrowBookModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Borrow Book</h2>
                <button class="close-btn" onclick="closeBorrowModal()">&times;</button>
            </div>
            <form id="borrowBookForm" method="POST" action="process_borrow.php">
                <input type="hidden" id="borrow_book_id" name="book_id">
                <div class="form-group">
                    <label for="student_id">Student ID</label>
                    <input type="text" id="student_id" name="student_id" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="borrow_date">Borrow Date</label>
                    <input type="date" id="borrow_date" name="borrow_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="return_date">Return Date</label>
                    <input type="date" id="return_date" name="return_date" class="form-control" required>
                </div>
                <div class="modal-buttons">
                    <button type="button" onclick="closeBorrowModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm Borrow</button>
                </div>
            </form>
        </div>
    </div>

    <!-- New Borrow Modal -->
    <div id="newBorrowModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Borrow</h2>
                <button class="close-btn" onclick="closeNewBorrowModal()">&times;</button>
            </div>
            <form id="newBorrowForm" method="POST" action="process_borrow.php" onsubmit="return validateForm(this);">
                <div class="form-group">
                    <label for="student_id">Student</label>
                    <select id="student_id" name="student_id" class="form-control" required>
                        <option value="">Select Student</option>
                        <?php
                        $student_sql = "SELECT student_id, CONCAT(firstname, ' ', lastname) as full_name 
                                      FROM tblstudent 
                                      ORDER BY firstname, lastname";
                        $student_result = $conn->query($student_sql);
                        while($student = $student_result->fetch_assoc()) {
                            echo "<option value='" . $student['student_id'] . "'>" . 
                                 htmlspecialchars($student['full_name']) . 
                                 "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="book_id">Book</label>
                    <select id="book_id" name="book_id" class="form-control" required>
                        <option value="">Select Book</option>
                        <?php
                        $book_sql = "SELECT b.* FROM tblbooks b 
                                   LEFT JOIN tblborrower br ON b.book_id = br.book_id 
                                   AND br.status = 'borrowed'
                                   WHERE br.borrow_id IS NULL 
                                   ORDER BY b.title";
                        $book_result = $conn->query($book_sql);
                        while($book = $book_result->fetch_assoc()) {
                            echo "<option value='" . $book['book_id'] . "'>" . 
                                 htmlspecialchars($book['title']) . 
                                 "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="borrow_date">Borrow Date</label>
                    <input type="date" id="borrow_date" name="borrow_date" class="form-control" required 
                           value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <label for="due_date">Due Date</label>
                    <input type="date" id="due_date" name="due_date" class="form-control" required 
                           value="<?php echo date('Y-m-d', strtotime('+7 days')); ?>">
                </div>
                <div class="modal-buttons">
                    <button type="button" onclick="closeNewBorrowModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm Borrow</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Borrow Modal -->
    <div id="viewBorrowModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Borrow Details</h2>
                <button class="close-btn" onclick="closeViewModal()">&times;</button>
            </div>
            <div id="viewBorrowContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Edit Borrow Modal -->
    <div id="editBorrowModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Borrow</h2>
                <button class="close-btn" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editBorrowForm" method="POST" action="process_borrow.php">
                <input type="hidden" id="edit_borrow_id" name="borrow_id">
                <div class="form-group">
                    <label for="edit_student_id">Student</label>
                    <select id="edit_student_id" name="student_id" class="form-control" required>
                        <option value="">Select Student</option>
                        <?php
                        $student_sql = "SELECT student_id, CONCAT(firstname, ' ', lastname) as full_name 
                                      FROM tblstudent 
                                      ORDER BY firstname, lastname";
                        $student_result = $conn->query($student_sql);
                        while($student = $student_result->fetch_assoc()) {
                            echo "<option value='" . $student['student_id'] . "'>" . 
                                 htmlspecialchars($student['full_name']) . 
                                 "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_book_id">Book</label>
                    <select id="edit_book_id" name="book_id" class="form-control" required>
                        <option value="">Select Book</option>
                        <?php
                        $book_sql = "SELECT * FROM tblbooks ORDER BY title";
                        $book_result = $conn->query($book_sql);
                        while($book = $book_result->fetch_assoc()) {
                            echo "<option value='" . $book['book_id'] . "'>" . 
                                 htmlspecialchars($book['title']) . 
                                 "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_borrow_date">Borrow Date</label>
                    <input type="date" id="edit_borrow_date" name="borrow_date" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_due_date">Return Date</label>
                    <input type="date" id="edit_due_date" name="due_date" class="form-control" required>
                </div>
                <div class="modal-buttons">
                    <button type="button" onclick="closeEditModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Borrow Modal -->
    <div id="deleteBorrowModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Delete Borrow Record</h2>
                <button class="close-btn" onclick="closeDeleteModal()">&times;</button>
            </div>
            <p>Are you sure you want to delete this borrow record?</p>
            <div class="modal-buttons">
                <button onclick="closeDeleteModal()" class="btn btn-secondary">Cancel</button>
                <button onclick="deleteBorrow(borrowToDelete)" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('.books-table').DataTable({
                "paging": true,
                "ordering": true,
                "info": true,
                "language": {
                    "search": "Search:",
                    "lengthMenu": "Show _MENU_ entries per page",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries"
                }
            });
        });

        // Modal Functions
        function openBorrowModal(bookId) {
            document.getElementById('borrow_book_id').value = bookId;
            
            // Set default dates
            const today = new Date().toISOString().split('T')[0];
            const returnDate = new Date();
            returnDate.setDate(returnDate.getDate() + 7); // 7 days from now
            
            document.getElementById('borrow_date').value = today;
            document.getElementById('return_date').value = returnDate.toISOString().split('T')[0];
            document.getElementById('borrowBookModal').style.display = 'flex';
        }

        function closeBorrowModal() {
            document.getElementById('borrowBookModal').style.display = 'none';
        }

        function openNewBorrowModal() {
            const modal = document.getElementById('newBorrowModal');
            if (modal) {
                modal.style.display = 'block';
                
                // Set default dates
                const today = new Date().toISOString().split('T')[0];
                const dueDate = new Date();
                dueDate.setDate(dueDate.getDate() + 7); // 7 days from now
                
                const borrowDateInput = document.getElementById('borrow_date');
                const dueDateInput = document.getElementById('due_date');
                
                if (borrowDateInput) borrowDateInput.value = today;
                if (dueDateInput) dueDateInput.value = dueDate.toISOString().split('T')[0];
            }
        }

        function closeNewBorrowModal() {
            const modal = document.getElementById('newBorrowModal');
            if (modal) {
                modal.style.display = 'none';
                // Reset form
                const form = document.getElementById('newBorrowForm');
                if (form) {
                    form.reset();
                }
            }
        }

        // View Borrow Modal Functions
        function openViewModal(borrowId) {
            fetch('get_borrow.php?borrow_id=' + borrowId)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('viewBorrowContent').innerHTML = `
                        <p><strong>Student:</strong> ${data.student_name}</p>
                        <p><strong>Book:</strong> ${data.book_title}</p>
                        <p><strong>Borrow Date:</strong> ${data.borrow_date}</p>
                        <p><strong>Due Date:</strong> ${data.due_date}</p>
                        <p><strong>Status:</strong> ${data.status}</p>
                    `;
                    document.getElementById('viewBorrowModal').style.display = 'flex';
                });
        }

        function closeViewModal() {
            document.getElementById('viewBorrowModal').style.display = 'none';
        }

        // Edit Borrow Modal Functions
        function openEditModal(borrowId) {
            fetch('get_borrow.php?borrow_id=' + borrowId)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_borrow_id').value = data.borrow_id;
                    document.getElementById('edit_student_id').value = data.student_id;
                    document.getElementById('edit_book_id').value = data.book_id;
                    document.getElementById('edit_borrow_date').value = data.borrow_date;
                    document.getElementById('edit_due_date').value = data.due_date;
                    document.getElementById('editBorrowModal').style.display = 'flex';
                });
        }

        function closeEditModal() {
            document.getElementById('editBorrowModal').style.display = 'none';
        }

        // Delete Borrow Modal Functions
        let borrowToDelete = null;

        function openDeleteModal(borrowId) {
            borrowToDelete = borrowId;
            document.getElementById('deleteBorrowModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteBorrowModal').style.display = 'none';
            borrowToDelete = null;
        }

        function deleteBorrow(borrowId) {
            if (!borrowId) return;
            
            fetch('process_borrow.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    borrow_id: borrowId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Borrow record deleted successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your request.');
            });
        }

        // Initialize when document is ready
        document.addEventListener('DOMContentLoaded', function() {
            const newBorrowForm = document.getElementById('newBorrowForm');
            if (newBorrowForm) {
                newBorrowForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Get form data
                    const formData = new FormData(this);
                    
                    // Debug: Log form data
                    console.log('Form Data:');
                    for (let pair of formData.entries()) {
                        console.log(pair[0] + ': ' + pair[1]);
                    }
                    
                    // Validate required fields
                    const studentId = formData.get('student_id');
                    const bookId = formData.get('book_id');
                    const borrowDate = formData.get('borrow_date');
                    const dueDate = formData.get('due_date');
                    
                    // Debug: Log field values
                    console.log('Field values:', {
                        studentId,
                        bookId,
                        borrowDate,
                        dueDate
                    });
                    
                    // Check if any required field is empty
                    if (!studentId || !bookId || !borrowDate || !dueDate) {
                        alert('Please fill in all required fields');
                        return;
                    }
                    
                    // Validate dates
                    const borrowDateObj = new Date(borrowDate);
                    const dueDateObj = new Date(dueDate);
                    
                    if (dueDateObj <= borrowDateObj) {
                        alert('Due date must be after borrow date');
                        return;
                    }
                    
                    // Show loading state
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.innerHTML;
                    submitButton.innerHTML = 'Processing...';
                    submitButton.disabled = true;
                    
                    // Send the request
                    fetch('process_borrow.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response headers:', response.headers);
                        return response.text().then(text => {
                            console.log('Raw response:', text);
                            try {
                                return JSON.parse(text);
                            } catch (e) {
                                console.error('Parse error:', e);
                                console.error('Raw text:', text);
                                throw new Error('Invalid JSON response from server');
                            }
                        });
                    })
                    .then(data => {
                        console.log('Parsed data:', data);
                        if (data.success) {
                            alert('Book borrowed successfully!');
                            closeNewBorrowModal();
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Unknown error occurred');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error: ' + error.message);
                    })
                    .finally(() => {
                        submitButton.innerHTML = originalText;
                        submitButton.disabled = false;
                    });
                });
            }
        });

        function validateForm(form) {
            const studentId = form.student_id.value;
            const bookId = form.book_id.value;
            const borrowDate = form.borrow_date.value;
            const dueDate = form.due_date.value;
            
            console.log('Form validation:', {
                studentId,
                bookId,
                borrowDate,
                dueDate
            });
            
            if (!studentId || !bookId || !borrowDate || !dueDate) {
                alert('Please fill in all required fields');
                return false;
            }
            
            // Validate dates
            const borrowDateObj = new Date(borrowDate);
            const dueDateObj = new Date(dueDate);
            
            if (dueDateObj <= borrowDateObj) {
                alert('Due date must be after borrow date');
                return false;
            }
            
            return true;
        }
    </script>
     <?php 

 include './components/top_bar.php';
 ?>
 
</body>
</html>
    