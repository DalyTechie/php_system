<?php
require_once 'session_check.php';
require_once 'db.php';

// Total Books
$totalBooks = $conn->query("SELECT COUNT(*) AS total FROM tblbooks")->fetch_assoc()['total'];

// Books Borrowed This Month
$booksBorrowedThisMonth = $conn->query("
    SELECT COUNT(*) AS total FROM tblborrower 
    WHERE MONTH(borrow_date) = MONTH(CURDATE()) AND YEAR(borrow_date) = YEAR(CURDATE())
")->fetch_assoc()['total'];

// Currently Borrowed
$currentlyBorrowed = $conn->query("
    SELECT COUNT(*) AS total FROM tblborrower WHERE status = 'borrowed'
")->fetch_assoc()['total'];

// Overdue Books - Count books with status = 'overdue'
$overdueBooks = $conn->query("
    SELECT COUNT(*) AS total FROM tblborrower WHERE status = 'overdue'
")->fetch_assoc()['total'];

include 'components/head.php';
?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
    body {
        font-family: 'Segoe UI', Arial, sans-serif;
    
        margin: 0;
        padding: 0;
    }
    .ml-64 {
        margin-left: 16rem;
    }
    .main-container {
        max-width: 1300px;
        margin: 40px auto 0 auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        padding: 32px 28px 28px 28px;
        margin-left: 300px;
    }
    .pkay {
        font-size: 2.5rem;
        font-weight: 400;
        color: #2d3748; /* dark slate */
        letter-spacing: 1px;
        text-align: center;
        margin: 2rem 0 1.5rem 0;
     /*   padding-bottom: 0.5rem;*/
        
        background: linear-gradient(90deg,rgb(8, 8, 8) 0%,rgb(7, 7, 7) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    .dashboard-cards {
        display: flex;
        gap: 24px;
        justify-content: center;
        margin-bottom: 32px;
        flex-wrap: wrap;
        
    }
    .card {
        display: inline-block;
        min-width: 220px;
        max-width: 260px;
        margin: 1rem 1.5rem 1.5rem 0;
        padding: 2rem 2.5rem;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 4px 24px rgba(44, 62, 80, 0.08), 0 1.5px 4px rgba(44, 62, 80, 0.08);
        font-size: 1.3rem;
        font-weight: 700;
        text-align: center;
        letter-spacing: 1px;
        border-bottom: 4px solid #4fd1c5;
        transition: transform 0.15s, box-shadow 0.15s;
        cursor: pointer;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }
    .card.total-books {
        border-bottom: 4px solid #4299e1; /* blue */
        color: #4299e1;
    }
    .card.borrowed-month {
        border-bottom: 4px solid #48bb78; /* green */
        color: #48bb78;
    }
    .card.currently-borrowed {
        border-bottom: 4px solid #ed8936; /* orange */
        color: #ed8936;
    }
    .card.overdue-books {
        border-bottom: 4px solid #f56565; /* red */
        color: #f56565;
    }
    .charts-section {
        margin-bottom: 36px;
        text-align: center;
    }
    .charts-section h2 {
        color: #2563eb;
        margin-bottom: 18px;
    }
    .table-section {
        margin-bottom: 36px;
    }
    .table-section h2 {
        color: #374151;
        margin-bottom: 12px;
    }
    .table-section table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border-radius: 8px;
        overflow: hidden;
        font-size: 15px;
        margin-bottom: 12px;
    }
    .table-section th, .table-section td {
        border: 1px solid #e5e7eb;
        padding: 13px 10px;
        text-align: left;
    }
    .table-section th {
        background: #f1f5f9;
        color: #374151;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .table-section tr:hover {
        background: #f3f4f6;
    }
    @media (max-width: 900px) {
        .main-container { padding: 10px; }
        .dashboard-cards { flex-direction: column; align-items: center; }
        .card { min-width: 90%; }
    }
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
        max-width: 800px;
        max-height: 80vh;
        overflow-y: auto;
    }
    
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .close:hover {
        color: #000;
    }
    
    .list-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-family: 'Segoe UI', Arial, sans-serif;
    }
    
    .list-table th, .list-table td {
        border: 1px solid #e5e7eb;
        padding: 12px;
        text-align: left;
    }
    
    .list-table th {
        background: #f8fafc;
        color: #374151;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-size: 12px;
    }
    
    .list-table tr:nth-child(even) {
        background: #f9fafb;
    }
    
    .list-table tr:hover {
        background: #f3f4f6;
        transition: background-color 0.2s;
    }
    
    /* Status badge animations */
    .list-table span {
        transition: all 0.2s ease;
    }
    
    .list-table span:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15) !important;
    }
    </style>
</head>
<body>
<?php include 'components/sidebar.php'; ?>
<div class="ml-64">
    <?php 
    include 'components/dashboard_stats.php';
 //       include 'components/top_bar.php';
    ?>
</div>

    <div class="main-container">
    <h1 class="pkay">Library Reports & Statistics</h1>
        <div class="dashboard-cards">
            <div class="card total-books" onclick="showBookList('all')">Total Books: <?= $totalBooks ?></div>
            <div class="card borrowed-month" onclick="showBookList('borrowed-month')">Books Borrowed This Month: <?= $booksBorrowedThisMonth ?></div>
            <div class="card currently-borrowed" onclick="showBookList('currently-borrowed')">Currently Borrowed: <?= $currentlyBorrowed ?></div>
            <div class="card overdue-books" onclick="showBookList('overdue')">Overdue Books: <?= $overdueBooks ?></div>
        </div>
        <div class="table-section">
            <h2>Most Borrowed Books</h2>
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Times Borrowed</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $mostBorrowedBooks = $conn->query("
                    
                        SELECT b.title, COUNT(*) AS times_borrowed
                        FROM tblborrower br
                        INNER JOIN tblbooks b ON br.book_id = b.book_id
                        GROUP BY br.book_id
                        ORDER BY times_borrowed DESC
                        LIMIT 5
                    ");
                    ?>
                    <?php while($row = $mostBorrowedBooks->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= $row['times_borrowed'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="table-section">
            <h2>Overdue Books</h2>
            <table>
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Student</th>
                        <th>Due Date</th>
                        <th>Days Overdue</th>
                    </tr>
                </thead>
                <tbody>
    <?php 
                    $overdueBooksList = $conn->query("
                        SELECT b.title, s.firstname, s.lastname, br.borrow_date, DATEDIFF(CURDATE(), br.return_date) AS days_overdue
                        FROM tblborrower br
                        INNER JOIN tblbooks b ON br.book_id = b.book_id
                        INNER JOIN tblstudent s ON br.student_id = s.student_id
                        WHERE br.status = 'borrowed' AND br.return_date < CURDATE()
                        ORDER BY days_overdue DESC
                    ");
                    ?>
                    <?php while($row = $overdueBooksList->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></td>
                            <td><?= htmlspecialchars($row['due_date']) ?></td>
                            <td><?= $row['days_overdue'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div> 

<!-- Add the modal for displaying lists -->
<div id="listModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modalTitle">Book List</h2>
        <div id="modalContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<?php include 'components/top_bar.php'; ?>
<script src="js/charts.js"></script>
<script>
function showBookList(type) {
    const modal = document.getElementById('listModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');
    
    // Set title based on type
    switch(type) {
        case 'all':
            modalTitle.textContent = 'All Books in Library';
            break;
        case 'borrowed-month':
            modalTitle.textContent = 'Books Borrowed This Month';
            break;
        case 'currently-borrowed':
            modalTitle.textContent = 'Currently Borrowed Books';
            break;
        case 'overdue':
            modalTitle.textContent = 'Overdue Books';
            break;
    }
    
    // Show loading
    modalContent.innerHTML = '<p>Loading...</p>';
    modal.style.display = 'block';
    
    // Fetch data using AJAX
    fetch(`get_report_data.php?type=${type}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                modalContent.innerHTML = createTableHTML(data.data, type, data.count);
            } else {
                modalContent.innerHTML = '<p>Error loading data: ' + (data.message || 'Unknown error') + '</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalContent.innerHTML = '<p>Error loading data: ' + error.message + '</p>';
        });
}

function createTableHTML(data, type, count) {
    if (!data || data.length === 0) {
        return '<p>No data available.</p>';
    }
    
    let tableHTML = `<div style="margin-bottom: 15px; font-weight: bold; color: #374151;">Total Count: ${count}</div>`;
    tableHTML += '<table class="list-table">';
    
    // Create headers based on type
    switch(type) {
        case 'all':
            tableHTML += `
                <thead>
                    <tr>
                        <th style="cursor: pointer; user-select: none;" onclick="sortTable('book_id')">
                            Book ID 
                            <span id="book_id_sort" style="margin-left: 5px;">▲▼</span>
                        </th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Category</th>
                        <th>Year</th>
                        <th>Publisher</th>
                    </tr>
                </thead>
            `;
            break;
        case 'borrowed-month':
        case 'currently-borrowed':
        case 'overdue':
            tableHTML += `
                <thead>
                    <tr>
                        <th style="cursor: pointer; user-select: none;" onclick="sortTable('borrow_id')">
                            Borrow ID 
                            <span id="borrow_id_sort" style="margin-left: 5px;">▲▼</span>
                        </th>
                        <th>Student Name</th>
                        <th>Book Title</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
            `;
            break;
    }
    
    tableHTML += '<tbody id="tableBody">';
    
    // Helper function to format N/A values with colored badges
    function formatValue(value) {
        if (!value || value === 'N/A') {
            return '<span style="background-color: #9ca3af; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;">N/A</span>';
        }
        return value;
    }
    
    data.forEach(item => {
        tableHTML += '<tr>';
        
        switch(type) {
            case 'all':
                tableHTML += `
                    <td data-sort="${item.book_id || 0}">${item.book_id || 'N/A'}</td>
                    <td>${item.title || 'N/A'}</td>
                    <td>${formatValue(item.author)}</td>
                    <td>${formatValue(item.isbn)}</td>
                    <td>${formatValue(item.category)}</td>
                    <td>${formatValue(item.publication_year)}</td>
                    <td>${formatValue(item.publisher)}</td>
                `;
                break;
            case 'borrowed-month':
            case 'currently-borrowed':
            case 'overdue':
                // Format dates to remove time
                const borrowDate = item.borrow_date ? item.borrow_date.split(' ')[0] : 'N/A';
                const dueDate = item.due_date ? item.due_date.split(' ')[0] : 'N/A';
                
                // Create colored status badge
                const statusBadge = createStatusBadge(item.status);
                
                tableHTML += `
                    <td data-sort="${item.borrow_id || 0}">${item.borrow_id || 'N/A'}</td>
                    <td>${item.student_name || 'N/A'}</td>
                    <td>${item.book_title || 'N/A'}</td>
                    <td>${borrowDate}</td>
                    <td>${formatValue(dueDate)}</td>
                    <td>${statusBadge}</td>
                `;
                break;
        }
        
        tableHTML += '</tr>';
    });
    
    tableHTML += '</tbody></table>';
    
    // Store the data for sorting
    window.currentTableData = data;
    window.currentTableType = type;
    
    return tableHTML;
}

// Add sorting functionality
let sortDirection = 'asc';

function sortTable(column) {
    const tbody = document.getElementById('tableBody');
    const rows = Array.from(tbody.getElementsByTagName('tr'));
    
    // Update sort indicator
    const sortIndicator = document.getElementById(column + '_sort');
    if (sortDirection === 'asc') {
        sortIndicator.textContent = '▲';
        sortDirection = 'desc';
    } else {
        sortIndicator.textContent = '▼';
        sortDirection = 'asc';
    }
    
    // Sort rows
    rows.sort((a, b) => {
        const aValue = parseInt(a.querySelector(`td[data-sort]`).getAttribute('data-sort')) || 0;
        const bValue = parseInt(b.querySelector(`td[data-sort]`).getAttribute('data-sort')) || 0;
        
        if (sortDirection === 'asc') {
            return aValue - bValue;
        } else {
            return bValue - aValue;
        }
    });
    
    // Reorder rows in the table
    rows.forEach(row => tbody.appendChild(row));
}

// Add hover effect for sortable headers
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .list-table th[onclick] {
            transition: background-color 0.2s;
        }
        .list-table th[onclick]:hover {
            background-color: #e5e7eb !important;
        }
    `;
    document.head.appendChild(style);
});

function createStatusBadge(status) {
    switch(status.toLowerCase()) {
        case 'overdue':
            return '<span style="background: #fee2e2; color: #dc2626; padding: 8px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; text-transform: uppercase; box-shadow: 0 2px 4px rgba(220, 38, 38, 0.1);">OVERDUE</span>';
            
        case 'borrowed':
            return '<span style="background: #dbeafe; color: #1d4ed8; padding: 8px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; text-transform: uppercase; box-shadow: 0 2px 4px rgba(29, 78, 216, 0.1);">BORROWED</span>';
            
        case 'returned':
            return '<span style="background: #dcfce7; color: #16a34a; padding: 8px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; text-transform: uppercase; box-shadow: 0 2px 4px rgba(22, 163, 74, 0.1);">RETURNED</span>';
            
        case 'lost':
            return '<span style="background: #fef9c3; color: #b45309; padding: 8px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; text-transform: uppercase; box-shadow: 0 2px 4px rgba(180, 83, 9, 0.1);">LOST</span>';
            
        case 'pending':
            return '<span style="background: #f3e8ff; color: #7c3aed; padding: 8px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; text-transform: uppercase; box-shadow: 0 2px 4px rgba(124, 58, 237, 0.1);">PENDING</span>';
            
        default:
            return `<span style="background: #f3f4f6; color:rgb(133, 45, 45); padding: 8px 12px; border-radius: 6px; font-weight: bold; font-size: 12px; text-transform: uppercase;">${status || 'N/A'}</span>`;
    }
}

function closeModal() {
    document.getElementById('listModal').style.display = 'none';
}

// Close modal when clicking outside of it
window.onclick = function(event) {
    const modal = document.getElementById('listModal');
    if (event.target === modal) {
        modal.style.display = 'none';
    }
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});
</script>
</body>
</html>