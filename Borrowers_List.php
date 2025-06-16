<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    // Include session check
    require_once 'session_check.php';
    ?>
    <title>Borrowers List</title>
    <?php include 'components/head.php'; // Include the common head section ?>
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

        .search-box {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .search-input {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            width: 300px;
        }

        .table-container {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .borrowers-table {
            width: 100%;
            border-collapse: collapse;
        }

        .borrowers-table th,
        .borrowers-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .borrowers-table th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
        }

        .borrowers-table tr:hover {
            background-color: #f9fafb;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-overdue {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .btn-primary {
            background-color: #4f46e5;
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background-color: #4338ca;
        }

        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background-color: white;
            border-top: 1px solid #e5e7eb;
        }

        .page-info {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .page-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .page-btn {
            padding: 0.5rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            background-color: white;
            color: #374151;
            cursor: pointer;
        }

        .page-btn:hover {
            background-color: #f9fafb;
        }

        .page-btn.active {
            background-color: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }
    </style>
</head>
<body>
    <?php include 'components/sidebar.php'; // Include the sidebar ?>
    <div class="main-container">
        <?php include 'components/top_bar.php'; // Include the top bar ?>
        <div class="page-header">
            <h1 class="text-2xl font-semibold text-gray-900">Borrowers List</h1>
            <div class="search-box">
                <input type="text" placeholder="Search borrowers..." class="search-input">
                <button class="btn btn-primary">Add New Borrower</button>
            </div>
        </div>

        <div class="table-container">
            <table class="borrowers-table">
                <thead>
                    <tr>
                        <th>Borrower Name</th>
                        <th>Book Title</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Include database connection
                    require("db.php");
                    
                    // Query to get borrowers list
                    $sql = "SELECT b.*, u.fullname, bk.title 
                           FROM tblborrower b 
                           JOIN tbluser u ON b.userid = u.userid 
                           JOIN tblbooks bk ON b.bookid = bk.bookid 
                           ORDER BY b.borrowdate DESC";
                    
                    $result = $conn->query($sql);
                    
                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $status = strtotime($row['duedate']) < time() ? 'overdue' : 'active';
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['fullname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . date('M d, Y', strtotime($row['borrowdate'])) . "</td>";
                            echo "<td>" . date('M d, Y', strtotime($row['duedate'])) . "</td>";
                            echo "<td><span class='status-badge status-" . $status . "'>" . 
                                 ucfirst($status) . "</span></td>";
                            echo "<td class='action-buttons'>
                                    <button class='btn btn-primary'>Return</button>
                                    <button class='btn btn-primary'>Extend</button>
                                 </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center py-4'>No borrowers found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            
            <div class="pagination">
                <span class="page-info">Showing 1 to 10 of 100 entries</span>
                <div class="page-buttons">
                    <button class="page-btn">Previous</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn">Next</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add search functionality
        document.querySelector('.search-input').addEventListener('input', function(e) {
            const searchText = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.borrowers-table tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchText) ? '' : 'none';
            });
        });
    </script>
</body>
</html>
    