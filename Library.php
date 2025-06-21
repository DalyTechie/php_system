<!DOCTYPE html>
<html lang="en">
<head>
<style>
    .library-table {
        width: 80%;
        border-collapse: collapse;
        font-size: 15px;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        margin-left: 300px;
        margin-top: 20px;
    }
    .library-table th, .library-table td {
        border: 1px solid #e5e7eb;
        padding: 13px 10px;
        text-align: left;
    }
    .library-table th {
        background: #f1f5f9;
        color: #374151;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .library-table tr:hover {
        background: #f3f4f6;
    }
    .search-form {
        display: flex;
        margin-left: 300px;
        gap: 10px;
        margin-bottom: 24px;
        margin-top: 20px;
    }
    .search-box-input {
        padding: 10px 16px;
        width: 320px;
        border: 1.5px solid #cbd5e1;
        border-radius: 6px;
        font-size: 16px;
        background: #f8fafc;
        transition: border-color 0.2s;
    }
    .search-box-input:focus {
        border-color: #6366f1;
        outline: none;
    }
    .search-btn, .reset-btn {
        padding: 10px 22px;
        background: #6366f1;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
    }
    .search-btn:hover {
        background: #4338ca;
    }
    .reset-btn {
        background: #e5e7eb;
        color: #374151;
    }
    .reset-btn:hover {
        background: #cbd5e1;
    }
    .availability-badge {
        display: inline-block;
        padding: 0.25em 0.8em;
        border-radius: 12px;
        font-size: 0.95em;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .status-available {
        background: #dcfce7;
        color: #16a34a;
    }
    .status-borrowed {
        background: #fee2e2;
        color: #dc2626;
    }
    .status-lost {
        background: #fef9c3;
        color: #b45309;
    }
    .book-cover {
        width: 50px;
        height: 70px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #e5e7eb;
    }
    .no-cover {
        width: 50px;
        height: 70px;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #9ca3af;
        font-size: 12px;
        text-align: center;
    }
</style>
    <?php
    // Include session check
    require_once 'session_check.php';
    include 'components/head.php';
    ?>
</head>
<body class="bg-gray-100">
    <?php include 'components/sidebar.php'; ?>
    
    <div class="ml-64">
        <?php 
        include 'components/dashboard_stats.php';
        ?>
    </div>

    <?php
    require("db.php");
    $search = isset($_POST['txtsearch']) ? trim($_POST['txtsearch']) : '';
    ?>
    
    <form method="post" class="search-form">
        <input type="text" name="txtsearch" class="search-box-input" placeholder="Search by Book Title, Author, or ISBN..." value="<?php echo isset($search) ? htmlspecialchars($search) : ''; ?>">
        <input type="submit" value="Search" name="btnsearch" class="search-btn">
        <button type="button" class="reset-btn" id="resetBtn">Reset</button>
    </form>
    
    <table class="library-table" id="libraryTable">
        <thead>
            <tr>
                <th id="sortBookId" style="cursor:pointer;">Book ID ▲▼</th>
                <th data-sort="title">Title</th>
                <th data-sort="author">Author</th>
                <th data-sort="isbn">ISBN</th>
                <th data-sort="category">Category</th>
                <th data-sort="publisher">Publisher</th>
                <th data-sort="publication_year">Year</th>
                <th data-sort="status">Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Helper function to safely handle null values and show "Null" for empty fields
        function safe_html($value) {
            if ($value === null || $value === '') {
                return '<span style="color: #dc2626; font-style: italic;">Null</span>';
            }
            return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        }
        
        // Build SQL query
        $sql = "SELECT book_id, title, author, isbn, publication_year, publisher, category 
                FROM tblbooks";
        if (!empty($search)) {
            $sql .= " WHERE title LIKE CONCAT('%', ?, '%') 
                      OR author LIKE CONCAT('%', ?, '%') 
                      OR isbn LIKE CONCAT('%', ?, '%')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $search, $search, $search);
        } else {
            $stmt = $conn->prepare($sql);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Determine status based on whether book is borrowed
                $status = 'Available';
                $statusClass = 'status-available';
                
                // Check if book is currently borrowed
                $check_borrowed = "SELECT COUNT(*) as borrowed_count FROM tblborrower 
                                  WHERE book_id = ? AND status = 'borrowed'";
                $borrowed_stmt = $conn->prepare($check_borrowed);
                $borrowed_stmt->bind_param('i', $row['book_id']);
                $borrowed_stmt->execute();
                $borrowed_result = $borrowed_stmt->get_result();
                $borrowed_row = $borrowed_result->fetch_assoc();
                
                if ($borrowed_row['borrowed_count'] > 0) {
                    $status = 'Borrowed';
                    $statusClass = 'status-borrowed';
                }
                
                echo "<tr>
                        <td>".safe_html($row['book_id'])."</td>
                        <td>".safe_html($row['title'])."</td>
                        <td>".safe_html($row['author'])."</td>
                        <td>".safe_html($row['isbn'])."</td>
                        <td>".safe_html($row['category'])."</td>
                        <td>".safe_html($row['publisher'])."</td>
                        <td>".safe_html($row['publication_year'])."</td>
                        <td><span class='availability-badge {$statusClass}'>".$status."</span></td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='8' style='text-align:center;'>No books found.</td></tr>";
        }
        ?>
        </tbody>
    </table>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // SEARCH
        const searchInput = document.querySelector('.search-box-input');
        searchInput.addEventListener('keyup', function() {
            let input = this.value.toLowerCase();
            let rows = document.querySelectorAll('#libraryTable tbody tr');
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? '' : 'none';
            });
        });

        // SORT
        const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;
        const comparer = (idx, asc) => (a, b) => {
            let v1 = getCellValue(asc ? a : b, idx);
            let v2 = getCellValue(asc ? b : a, idx);
            return !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.localeCompare(v2);
        };

        // Book ID sorting
        let asc = true;
        document.getElementById('sortBookId').addEventListener('click', function() {
            const table = document.getElementById('libraryTable');
            const tbody = table.tBodies[0];
            const rows = Array.from(tbody.querySelectorAll('tr'));
            rows.sort(function(a, b) {
                let idA = parseInt(a.cells[0].innerText, 10);
                let idB = parseInt(b.cells[0].innerText, 10);
                return asc ? idA - idB : idB - idA;
            });
            rows.forEach(row => tbody.appendChild(row));
            asc = !asc;
            this.innerHTML = 'Book ID ' + (asc ? '▲▼' : '▼▲');
        });

        // Other column sorting
        document.querySelectorAll('#libraryTable th[data-sort]').forEach((th, idx) => {
            th.style.cursor = 'pointer';
            th.title = "Sort by " + th.textContent;
            th.addEventListener('click', function() {
                const table = th.closest('table');
                Array.from(table.querySelectorAll('tbody tr'))
                    .sort(comparer(idx + 1, this.asc = !this.asc)) // +1 because first column is Book ID
                    .forEach(tr => table.querySelector('tbody').appendChild(tr));
            });
        });
    });

    document.getElementById('resetBtn').onclick = function() {
        document.querySelector('.search-box-input').value = '';
        document.forms[0].submit();
    };
    </script>

    <?php include 'components/top_bar.php'; ?>
    <script src="js/charts.js"></script>
</body>
</html>