<!DOCTYPE html>
<html lang="en">
<head>
<style>
    .borrow-table {
        width: 80%;
        border-collapse: collapse;
        font-size: 15px;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        margin-left: 300px;
    }
    .borrow-table th, .borrow-table td {
        border: 1px solid #e5e7eb;
        padding: 13px 10px;
        text-align: left;
    }
    .borrow-table th {
        background: #f1f5f9;
        color: #374151;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .borrow-table tr:hover {
        background: #f3f4f6;
    }
    .search-form {
        display: flex;
       margin-left: 300px;
    
        gap: 10px;
        margin-bottom: 24px;
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
    .go-home-btn {
        display: inline-block;
        margin: 20px auto 30px auto;
        padding: 12px 28px;
        background: #6366f1;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 17px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(99,102,241,0.08);
        transition: background 0.2s, color 0.2s;
    }
    .go-home-btn:hover {
        background: #4338ca;
        color: #e0e7ff;
    }
    .status-badge {
        display: inline-block;
        padding: 0.25em 0.8em;
        border-radius: 12px;
        font-size: 0.95em;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .status-borrowed {
        background: #fee2e2;
        color: #dc2626;
    }
    .status-returned {
        background: #dcfce7;
        color: #16a34a;
    }
    .status-overdue {
        background: #fef9c3;
        color: #b45309;
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
 //       include 'components/top_bar.php';
    ?>
</div>

    <?php
    require("db.php");

    // Get search term and date range
    $search = isset($_POST['txtsearch']) ? trim($_POST['txtsearch']) : '';
    $start_borrow = isset($_POST['start_borrow']) ? $_POST['start_borrow'] : '';
    $end_borrow = isset($_POST['end_borrow']) ? $_POST['end_borrow'] : '';
    $start_return = isset($_POST['start_return']) ? $_POST['start_return'] : '';
    $end_return = isset($_POST['end_return']) ? $_POST['end_return'] : '';
    ?>

    <!-- Search Form -->
    <form method="post" class="search-form" style="align-items: center;">
        <!-- Borrow Date range filter -->
        <label for="start_borrow" style="margin-left: 1rem; font-weight: 500;">Borrow Date To :</label>
        <input type="date" id="start_borrow" name="start_borrow" class="search-box-input" style="width: 150px;" value="<?php echo htmlspecialchars($start_borrow); ?>">
        <!-- Return Date range filter -->
        <label for="end_return" style="margin-left: 0.5rem; font-weight: 500;">Return Date To:</label>
        <input type="date" id="end_return" name="end_return" class="search-box-input" style="width: 150px;" value="<?php echo htmlspecialchars($end_return); ?>">

        <input type="submit" value="Search" name="btnsearch" class="search-btn">
        <button type="button" class="reset-btn" id="resetBtn">Reset</button>
    </form>
    
    <table class="borrow-table" id="borrowTable">
        <thead>
            <tr>
                <th id="sortBorrowId" style="cursor:pointer;">Borrow ID ▲▼</th>
                <th data-sort="student_name">Student Name</th>
                <th data-sort="book_title">Book Title</th>
                <th data-sort="borrow_date">Borrow-Date</th>
                <th data-sort="return_date">Return-Date</th>
                <th data-sort="status">Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
        // Build SQL with JOINs
        $sql = "SELECT br.borrow_id, s.firstname, s.lastname, b.title, br.borrow_date, br.return_date, br.status
                FROM tblborrower br
                INNER JOIN tblstudent s ON br.student_id = s.student_id
                INNER JOIN tblbooks b ON br.book_id = b.book_id";

        // Build WHERE conditions
        $conditions = [];
        $params = [];
        $types = '';

        if (!empty($search)) {
            $conditions[] = "br.borrow_id LIKE ?";
            $params[] = "%" . $search . "%";
            $types .= 's';
        }
        if (!empty($start_borrow)) {
            $conditions[] = "DATE(br.borrow_date) >= ?";
            $params[] = $start_borrow;
            $types .= 's';
        }
        if (!empty($end_borrow)) {
            $conditions[] = "DATE(br.borrow_date) <= ?";
            $params[] = $end_borrow;
            $types .= 's';
        }
        if (!empty($start_return)) {
            $conditions[] = "DATE(br.return_date) >= ?";
            $params[] = $start_return;
            $types .= 's';
        }
        if (!empty($end_return)) {
            $conditions[] = "DATE(br.return_date) <= ?";
            $params[] = $end_return;
            $types .= 's';
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        // Prepare and execute statement
        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Determine status class based on status value
                $statusClass = '';
                switch(strtolower($row['status'])) {
                    case 'borrowed':
                        $statusClass = 'status-borrowed';
                        break;
                    case 'returned':
                        $statusClass = 'status-returned';
                        break;
                    case 'overdue':
                        $statusClass = 'status-overdue';
                        break;
                    default:
                        $statusClass = 'status-borrowed'; // default fallback
                }
                
                echo "<tr>
                        <td>".htmlspecialchars($row['borrow_id'])."</td>
                        <td>".htmlspecialchars($row['firstname'].' '.$row['lastname'])."</td>
                        <td>".htmlspecialchars($row['title'])."</td>
                        <td>".htmlspecialchars(!empty($row['borrow_date']) ? date('m/d/Y', strtotime($row['borrow_date'])) : '')."</td>
                        <td>".htmlspecialchars(!empty($row['return_date']) ? date('m/d/Y', strtotime($row['return_date'])) : '')."</td>
                        <td><span class='status-badge {$statusClass}'>".htmlspecialchars($row['status'])."</span></td>
                    </tr>";
            }
        } else {
            echo "<tr><td colspan='6' style='text-align:center;'>No borrow records found.</td></tr>";
        }
        ?>
        </tbody>
    </table>
    <?php 'components/top_bar.php'; ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // SEARCH
        const searchInput = document.querySelector('.search-box-input');
        
        // This keyup listener can be removed if you only want to search on button click
        // searchInput.addEventListener('keyup', function() {
        //     let input = this.value.toLowerCase();
        //     let rows = document.querySelectorAll('#borrowTable tbody tr');
        //     rows.forEach(row => {
        //         let text = row.textContent.toLowerCase();
        //         row.style.display = text.includes(input) ? '' : 'none';
        //     });
        // });

        // SORT
        const getCellValue = (tr, idx) => tr.children[idx].innerText || tr.children[idx].textContent;
        const comparer = (idx, asc) => (a, b) => {
            let v1 = getCellValue(asc ? a : b, idx);
            let v2 = getCellValue(asc ? b : a, idx);
            // Compare as numbers if possible, else as strings
            return !isNaN(v1) && !isNaN(v2) ? v1 - v2 : v1.localeCompare(v2);
        };

        document.querySelectorAll('#borrowTable th').forEach((th, idx) => {
            if (th.dataset.sort === "borrow_id") {
                th.style.cursor = 'pointer';
                th.title = "Sort by Borrow ID";
                th.addEventListener('click', function() {
                    const table = th.closest('table');
                    Array.from(table.querySelectorAll('tbody tr'))
                        .sort(comparer(idx, this.asc = !this.asc))
                        .forEach(tr => table.querySelector('tbody').appendChild(tr));
                });
            }
        });

        let asc = true;
        document.getElementById('sortBorrowId').addEventListener('click', function() {
            const table = document.getElementById('borrowTable');
            const tbody = table.tBodies[0];
            const rows = Array.from(tbody.querySelectorAll('tr'));
            rows.sort(function(a, b) {
                // Get Borrow ID from first cell, parse as integer
                let idA = parseInt(a.cells[0].innerText, 10);
                let idB = parseInt(b.cells[0].innerText, 10);
                return asc ? idA - idB : idB - idA;
            });
            // Re-append sorted rows
            rows.forEach(row => tbody.appendChild(row));
            asc = !asc;
            // Optionally, update the arrow
            this.innerHTML = 'Borrow ID ' + (asc ? '▲▼' : '▼▲');
        });
    });
    document.getElementById('resetBtn').onclick = function() {
        document.getElementById('start_borrow').value = '';
        document.getElementById('end_return').value = '';
        document.forms[0].submit();
    };
    </script>



<?php include 'components/top_bar.php'; ?>
<script src="js/charts.js"></script>
</body>
</html>