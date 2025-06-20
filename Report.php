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

// Overdue Books
$overdueBooks = $conn->query("
    SELECT COUNT(*) AS total FROM tblborrower WHERE status = 'borrowed' AND return_date < CURDATE()
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
    }
    .card:hover {
        transform: translateY(-6px) scale(1.03);
        box-shadow: 0 8px 32px rgba(44, 62, 80, 0.16), 0 3px 8px rgba(44, 62, 80, 0.12);
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
            <div class="card total-books">Total Books: <?= $totalBooks ?></div>
            <div class="card borrowed-month">Books Borrowed This Month: <?= $booksBorrowedThisMonth ?></div>
            <div class="card currently-borrowed">Currently Borrowed: <?= $currentlyBorrowed ?></div>
            <div class="card overdue-books">Overdue Books: <?= $overdueBooks ?></div>
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

<?php include 'components/top_bar.php'; ?>
<script src="js/charts.js"></script>
</body>
</html>