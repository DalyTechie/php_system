<?php
require_once 'db.php';

echo "<h2>Overdue Books Working Check</h2>";
echo "<h3>Current Date: " . date('Y-m-d') . "</h3>";

// Check all borrowed books
echo "<h3>All Borrowed Books:</h3>";
$sql = "SELECT br.borrow_id, 
               CONCAT(s.firstname, ' ', s.lastname) as student_name,
               b.title as book_title,
               br.borrow_date,
               br.status
        FROM tblborrower br
        INNER JOIN tblstudent s ON br.student_id = s.student_id
        INNER JOIN tblbooks b ON br.book_id = b.book_id
        WHERE br.status = 'borrowed'
        ORDER BY br.borrow_date DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Borrow ID</th><th>Student</th><th>Book</th><th>Borrow Date</th><th>Status</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['borrow_id'] . "</td>";
        echo "<td>" . $row['student_name'] . "</td>";
        echo "<td>" . $row['book_title'] . "</td>";
        echo "<td>" . $row['borrow_date'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p><strong>Total Borrowed Books:</strong> " . $result->num_rows . "</p>";
} else {
    echo "<p>No borrowed books found.</p>";
}

// Check books with status = 'overdue'
echo "<h3>Books with status = 'overdue':</h3>";
$overdue_sql = "SELECT COUNT(*) AS total FROM tblborrower WHERE status = 'overdue'";
$overdue_result = $conn->query($overdue_sql);
$overdue_count = $overdue_result->fetch_assoc()['total'];
echo "<p><strong>Books with status = 'overdue':</strong> " . $overdue_count . "</p>";

// Show all books with status = 'overdue'
echo "<h3>All Books with status = 'overdue':</h3>";
$overdue_books_sql = "SELECT br.borrow_id, 
                             CONCAT(s.firstname, ' ', s.lastname) as student_name,
                             b.title as book_title,
                             br.borrow_date,
                             br.status
                      FROM tblborrower br
                      INNER JOIN tblstudent s ON br.student_id = s.student_id
                      INNER JOIN tblbooks b ON br.book_id = b.book_id
                      WHERE br.status = 'overdue'
                      ORDER BY br.borrow_date DESC";

$overdue_books_result = $conn->query($overdue_books_sql);
if ($overdue_books_result && $overdue_books_result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Borrow ID</th><th>Student</th><th>Book</th><th>Borrow Date</th><th>Status</th></tr>";
    
    while ($row = $overdue_books_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['borrow_id'] . "</td>";
        echo "<td>" . $row['student_name'] . "</td>";
        echo "<td>" . $row['book_title'] . "</td>";
        echo "<td>" . $row['borrow_date'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p><strong>Total Books with status = 'overdue':</strong> " . $overdue_books_result->num_rows . "</p>";
} else {
    echo "<p>No books with status = 'overdue' found.</p>";
}

// Check books that should be overdue (7 days after borrow)
echo "<h3>Books that should be overdue (7 days after borrow):</h3>";
$should_overdue_7_sql = "SELECT COUNT(*) AS total FROM tblborrower 
                         WHERE status = 'borrowed' 
                         AND DATE_ADD(DATE(borrow_date), INTERVAL 7 DAY) < CURDATE()";
$should_overdue_7_result = $conn->query($should_overdue_7_sql);
$should_overdue_7_count = $should_overdue_7_result->fetch_assoc()['total'];
echo "<p><strong>Books that should be overdue (7 days):</strong> " . $should_overdue_7_count . "</p>";

// Check books that should be overdue (14 days after borrow)
echo "<h3>Books that should be overdue (14 days after borrow):</h3>";
$should_overdue_14_sql = "SELECT COUNT(*) AS total FROM tblborrower 
                          WHERE status = 'borrowed' 
                          AND DATE_ADD(DATE(borrow_date), INTERVAL 14 DAY) < CURDATE()";
$should_overdue_14_result = $conn->query($should_overdue_14_sql);
$should_overdue_14_count = $should_overdue_14_result->fetch_assoc()['total'];
echo "<p><strong>Books that should be overdue (14 days):</strong> " . $should_overdue_14_count . "</p>";

// Check books that should be overdue (30 days after borrow)
echo "<h3>Books that should be overdue (30 days after borrow):</h3>";
$should_overdue_30_sql = "SELECT COUNT(*) AS total FROM tblborrower 
                          WHERE status = 'borrowed' 
                          AND DATE_ADD(DATE(borrow_date), INTERVAL 30 DAY) < CURDATE()";
$should_overdue_30_result = $conn->query($should_overdue_30_sql);
$should_overdue_30_count = $should_overdue_30_result->fetch_assoc()['total'];
echo "<p><strong>Books that should be overdue (30 days):</strong> " . $should_overdue_30_count . "</p>";

// Check books that should be overdue (60 days after borrow)
echo "<h3>Books that should be overdue (60 days after borrow):</h3>";
$should_overdue_60_sql = "SELECT COUNT(*) AS total FROM tblborrower 
                          WHERE status = 'borrowed' 
                          AND DATE_ADD(DATE(borrow_date), INTERVAL 60 DAY) < CURDATE()";
$should_overdue_60_result = $conn->query($should_overdue_60_sql);
$should_overdue_60_count = $should_overdue_60_result->fetch_assoc()['total'];
echo "<p><strong>Books that should be overdue (60 days):</strong> " . $should_overdue_60_count . "</p>";

// Check books that should be overdue (90 days after borrow)
echo "<h3>Books that should be overdue (90 days after borrow):</h3>";
$should_overdue_90_sql = "SELECT COUNT(*) AS total FROM tblborrower 
                          WHERE status = 'borrowed' 
                          AND DATE_ADD(DATE(borrow_date), INTERVAL 90 DAY) < CURDATE()";
$should_overdue_90_result = $conn->query($should_overdue_90_sql);
$should_overdue_90_count = $should_overdue_90_result->fetch_assoc()['total'];
echo "<p><strong>Books that should be overdue (90 days):</strong> " . $should_overdue_90_count . "</p>";

// Check books that should be overdue (365 days after borrow)
echo "<h3>Books that should be overdue (365 days after borrow):</h3>";
$should_overdue_365_sql = "SELECT COUNT(*) AS total FROM tblborrower 
                           WHERE status = 'borrowed' 
                           AND DATE_ADD(DATE(borrow_date), INTERVAL 365 DAY) < CURDATE()";
$should_overdue_365_result = $conn->query($should_overdue_365_sql);
$should_overdue_365_count = $should_overdue_365_result->fetch_assoc()['total'];
echo "<p><strong>Books that should be overdue (365 days):</strong> " . $should_overdue_365_count . "</p>";

// Test the exact query from Report.php
echo "<h3>Testing Report.php Query:</h3>";
$report_query = "SELECT COUNT(*) AS total FROM tblborrower 
                 WHERE status = 'borrowed' 
                 AND DATE_ADD(DATE(borrow_date), INTERVAL 30 DAY) < CURDATE()";
$report_result = $conn->query($report_query);
if ($report_result) {
    $report_count = $report_result->fetch_assoc()['total'];
    echo "<p><strong>Report.php Query Result:</strong> " . $report_count . "</p>";
} else {
    echo "<p><strong>Query Error:</strong> " . $conn->error . "</p>";
}
?> 