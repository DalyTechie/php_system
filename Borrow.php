<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once 'session_check.php';
    ?>
    <title>Borrow Books - Library Management System</title>
    <?php include 'components/head.php'; ?>
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
    </style>
</head>
<body>
    <?php include 'components/sidebar.php'; ?>
    
    <div class="main-container">
        <?php include 'components/top_bar.php'; ?>

        <div class="page-header">
            <h1>Borrow Books</h1>
            <div class="header-actions">
                <input type="text" id="searchInput" placeholder="Search books..." class="search-input" onkeyup="searchBooks()">
            </div>
        </div>

        <div class="table-container">
            <table class="books-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Publisher</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="booksTableBody">
                    <?php
                    require("db.php");
                    
                    $sql = "SELECT b.*, 
                           CASE WHEN br.borrow_id IS NOT NULL THEN 'Borrowed' ELSE 'Available' END as status 
                           FROM tblbooks b 
                           LEFT JOIN tblborrow br ON b.book_id = br.book_id AND br.return_date IS NULL 
                           ORDER BY b.book_id ASC";
                    $result = $conn->query($sql);
                    
                    if (!$result) {
                        echo "<tr><td colspan='7' class='text-center'>Error executing query: " . $conn->error . "</td></tr>";
                    } else if ($result->num_rows === 0) {
                        echo "<tr><td colspan='7' class='text-center'>No books found in the database</td></tr>";
                    } else {
                        $counter = 1;
                        while($row = $result->fetch_assoc()) {
                            $statusClass = $row['status'] === 'Available' ? 'status-available' : 'status-borrowed';
                            echo "<tr>";
                            echo "<td>" . $counter . "</td>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['publisher']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                            echo "<td class='" . $statusClass . "'>" . $row['status'] . "</td>";
                            echo "<td class='actions'>";
                            if ($row['status'] === 'Available') {
                                echo "<button onclick='openBorrowModal(\"" . htmlspecialchars($row['book_id']) . "\")' class='btn btn-success btn-sm'>
                                        <i class='fas fa-book'></i> Borrow
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

    <script>
        // Search Function
        function searchBooks() {
            const searchInput = document.getElementById('searchInput');
            const filter = searchInput.value.toLowerCase();
            const tbody = document.getElementById('booksTableBody');
            const rows = tbody.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;

                for (let j = 0; j < cells.length - 1; j++) {
                    const cell = cells[j];
                    if (cell) {
                        const text = cell.textContent || cell.innerText;
                        if (text.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }

                row.style.display = found ? '' : 'none';
            }
        }

        // Borrow Modal Functions
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
    </script>
</body>
</html> 