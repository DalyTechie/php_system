<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once './session_check.php';
    require_once 'db.php';

    // Fetch students who have borrowed books
    $students = [];
    $student_result = $conn->query("
        SELECT s.student_id, s.firstname, s.lastname
        FROM tblstudent s
        INNER JOIN tblborrower br ON s.student_id = br.student_id
        WHERE br.status = 'borrowed'
        GROUP BY s.student_id
    ");
    if ($student_result) {
        while ($s = $student_result->fetch_assoc()) {
            $students[] = $s;
        }
    }

    // Fetch books that are currently borrowed
    $books = [];
    $book_result = $conn->query("
        SELECT b.book_id, b.title
        FROM tblbooks b
        INNER JOIN tblborrower br ON b.book_id = br.book_id
        WHERE br.status = 'borrowed'
        GROUP BY b.book_id
    ");
    if ($book_result) {
        while ($b = $book_result->fetch_assoc()) {
            $books[] = $b;
        }
    }
    ?>
    <title>Borrow Books - Library Management System</title>
    <?php include './components/head.php'; ?>
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
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 500px;
            position: relative;
            margin: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .modal-header h2 {
            font-size: 1.5rem;
            margin: 0;
            color: #333;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #666;
            padding: 0.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .modal-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
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

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn-view {
            border: 2px solid #4f46e5; /* blue */
            background: none;
            color: #4f46e5;
        }
        .btn-edit {
            border: 2px solid #22c55e; /* green */
            background: none;
            color: #22c55e;
        }
        .btn-delete {
            border: 2px solid #ef4444; /* red */
            background: none;
            color: #ef4444;
        }
        .btn-view:hover,
        .btn-edit:hover,
        .btn-delete:hover {
            opacity: 0.8;
            background: #f3f4f6; /* subtle hover effect */
        }
    </style>
</head>
<body>
    <?php include './components/sidebar.php'; ?>
    
    <div class="main-container">
  

        <div class="page-header">
            <h1>Return Books</h1>
            <div class="header-actions">
                <input type="text" id="searchInput" placeholder="Search books..." class="search-input" onkeyup="searchBooks()">
                <button type="button" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Return
                </button>
            </div>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div style="color: red; margin-bottom: 1em;">
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['msg'])): ?>
            <div style="color: green; margin-bottom: 1em;">
                <?= htmlspecialchars($_GET['msg']) ?>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <table class="books-table">
                <thead>
                    <tr>
                        <th id="sortStudentId" style="cursor:pointer;">
                            Student ID <span id="studentIdArrow">▲▼</span>
                        </th>
                        <!-- <th>BookID</th> -->
                        <th>Student Name</th>
                        <th>Title</th>
                        <!-- <th>Course</th> -->
                        <!-- <th>Borrow Date</th> -->
                        <th>Return Date</th>
                        <!-- <th>Status</th> -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="booksTableBody">
                    <?php
                    require("db.php");
                    
                    $sql = "select * from vreturn";
                    $result = $conn->query($sql);
                    
                    if (!$result) {
                        echo "<tr><td colspan='7' class='text-center'>Error executing query: " . $conn->error . "</td></tr>";
                    } else if ($result->num_rows === 0) {
                        echo "<tr><td colspan='7' class='text-center'>No books found in the database</td></tr>";
                    } else {
                        while($row = $result->fetch_assoc()) {
                            $statusClass = $row['status'] === 'Available' ? 'status-available' : 'status-borrowed';
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            // echo "<td>" . htmlspecialchars($row['course_name']) . "</td>";
                            // echo "<td>" . htmlspecialchars($row['borrow_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['return_date']) . "</td>";
                            echo "<td class='actions'>
                                    <button class='btn btn-sm btn-view' onclick='viewDetails(" . json_encode($row) . ")'>
                                        👁️ View
                                    </button>
                                    <button class='btn btn-sm btn-edit' onclick='openEditReturnModal(" . $row['borrow_id'] . ", \"" . $row['return_date'] . "\")'>
                                        ✏️ Edit
                                    </button>
                                    <form method='POST' action='delete_return.php' style='display:inline;' onsubmit='return confirm(\"Are you sure you want to delete this return record?\");'>
                                        <input type='hidden' name='borrow_id' value='" . $row['borrow_id'] . "'>
                                        <button type='submit' class='btn btn-sm btn-delete'>
                                            🗑️ Delete
                                        </button>
                                    </form>
                                </td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- View Details Modal -->
    <div id="viewDetailsModal" class="modal"></div>

    <!-- Add Return Modal -->
    <div id="addReturnModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Return</h2>
                <button class="close-btn" onclick="closeAddReturnModal()">&times;</button>
            </div>
            <form id="addReturnForm" method="POST" action="add_return.php">
                <div class="form-group">
                    <label for="student_id">Student</label>
                    <select id="student_id" name="student_id" class="form-control" required>
                        <option value="">Select a student</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?= htmlspecialchars($student['student_id']) ?>">
                            <?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="book_id">Book</label>
                    <select id="book_id" name="book_id" class="form-control" required>
                        <option value="">Select a book</option>
                        <?php foreach ($books as $book): ?>
                            <option value="<?= htmlspecialchars($book['book_id']) ?>">
                   <?= htmlspecialchars($book['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="return_date">Return Date</label>
                    <input type="date" id="return_date" name="return_date" class="form-control" required>
                </div>
                <div class="modal-buttons">
                    <button type="button" onclick="closeAddReturnModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Return</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Return Modal -->
    <div id="editReturnModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Return</h2>
                <button class="close-btn" onclick="closeEditReturnModal()">&times;</button>
            </div>
            <form id="editReturnForm" method="POST" action="edit_return.php">
                <input type="hidden" id="edit_borrow_id" name="borrow_id">
                <div class="form-group">
                    <label for="edit_return_date">Return Date</label>
                    <input type="date" id="edit_return_date" name="return_date" class="form-control" required>
                </div>
                <div class="modal-buttons">
                    <button type="button" onclick="closeEditReturnModal()" class="btn btn-secondary">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Return</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function searchBooks() {
            var input = document.getElementById("searchInput");
            var filter = input.value.toUpperCase();
            var tbody = document.getElementById("booksTableBody");
            var tr = tbody.getElementsByTagName("tr");

            for (var i = 0; i < tr.length; i++) {
                var found = false;
                var td = tr[i].getElementsByTagName("td");
                for (var j = 0; j < td.length - 1; j++) {
                    if (td[j]) {
                        var txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = found ? "" : "none";
            }
        }

        function viewDetails(rowData) {
            const detailsHtml = `
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Return Details</h2>
                        <button class="close-btn" onclick="hideDetailsModal()">&times;</button>
                    </div>
                    <div class="form-group">
                        <label>Student ID:</label>
                        <p>${rowData.student_id}</p>
                    </div>
                    <div class="form-group">
                        <label>Student Name:</label>
                        <p>${rowData.first_name} ${rowData.last_name}</p>
                    </div>
                    <div class="form-group">
                        <label>Book Title:</label>
                        <p>${rowData.title}</p>
                    </div>
                    <div class="form-group">
                        <label>Return Date:</label>
                        <p>${rowData.return_date}</p>
                    </div>
                    <div class="form-group">
                        <label>Status:</label>
                        <p>${rowData.status}</p>
                    </div>
                    <div class="modal-buttons">
                        <button type="button" onclick="hideDetailsModal()" class="btn btn-primary">Close</button>
                    </div>
                </div>`;

            const modal = document.getElementById("viewDetailsModal");
            modal.innerHTML = detailsHtml;
            modal.style.display = "flex";
        }

        function hideDetailsModal() {
            document.getElementById("viewDetailsModal").style.display = "none";
        }

        window.onclick = function(event) {
            const modal = document.getElementById("viewDetailsModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Open Add Return Modal
        document.querySelector('.header-actions .btn.btn-primary').onclick = function() {
            document.getElementById('addReturnModal').style.display = 'flex';
        };

        // Close Add Return Modal
        function closeAddReturnModal() {
            document.getElementById('addReturnModal').style.display = 'none';
            // Optionally reset the form fields
            document.getElementById('addReturnForm').reset();
        }

        function openEditReturnModal(borrow_id, return_date) {
            document.getElementById('edit_borrow_id').value = borrow_id;
            document.getElementById('edit_return_date').value = return_date;
            document.getElementById('editReturnModal').style.display = 'flex';
        }

        function closeEditReturnModal() {
            document.getElementById('editReturnModal').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            let ascStudent = true;
            const th = document.getElementById('sortStudentId');
            const arrow = document.getElementById('studentIdArrow');
            th.addEventListener('click', function() {
                const tbody = document.getElementById('booksTableBody');
                const rows = Array.from(tbody.querySelectorAll('tr'));
                rows.sort(function(a, b) {
                    // Student ID is in the first column (index 0)
                    let idA = parseInt(a.cells[0].innerText.trim(), 10);
                    let idB = parseInt(b.cells[0].innerText.trim(), 10);
                    // If not a number, fallback to string comparison
                    if (isNaN(idA) || isNaN(idB)) {
                        let strA = a.cells[0].innerText.trim();
                        let strB = b.cells[0].innerText.trim();
                        return ascStudent
                            ? strA.localeCompare(strB, undefined, {numeric: true})
                            : strB.localeCompare(strA, undefined, {numeric: true});
                    }
                    return ascStudent ? idA - idB : idB - idA;
                });
                rows.forEach(row => tbody.appendChild(row));
                ascStudent = !ascStudent;
                arrow.textContent = ascStudent ? '▲▼' : '▼▲';
            });
        });
    </script>
        <?php include './components/top_bar.php'; ?>
</body>
</html>
    