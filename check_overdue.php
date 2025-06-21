<?php
require_once 'db.php';

echo "<h2>Overdue Books Diagnostic</h2>";

// Check current date
echo "<h3>Current Date: " . date('Y-m-d') . "</h3>";

// Check all borrowed books with their due dates
echo "<h3>All Borrowed Books:</h3>";
$sql = "SELECT br.borrow_id, CONCAT(s.firstname, ' ', s.lastname) as student_name, 
               b.title as book_title, br.borrow_date, 
               COALESCE(br.due_date, DATE_ADD(DATE(br.borrow_date), INTERVAL 14 DAY)) as calculated_due_date,
               br.due_date as actual_due_date,
               br.status
        FROM tblborrower br
        INNER JOIN tblstudent s ON br.student_id = s.student_id
        INNER JOIN tblbooks b ON br.book_id = b.book_id
        WHERE br.status = 'borrowed'
        ORDER BY br.borrow_id";

$result = $conn->query($sql);
echo "<table border='1'>";
echo "<tr><th>Borrow ID</th><th>Student</th><th>Book</th><th>Borrow Date</th><th>Actual Due Date</th><th>Calculated Due Date</th><th>Is Overdue</th></tr>";

while ($row = $result->fetch_assoc()) {
    $calculatedDue = $row['calculated_due_date'];
    $isOverdue = $calculatedDue < date('Y-m-d');
    $overdueStatus = $isOverdue ? 'YES' : 'NO';
    $overdueColor = $isOverdue ? 'red' : 'green';
    
    echo "<tr>";
    echo "<td>" . $row['borrow_id'] . "</td>";
    echo "<td>" . $row['student_name'] . "</td>";
    echo "<td>" . $row['book_title'] . "</td>";
    echo "<td>" . $row['borrow_date'] . "</td>";
    echo "<td>" . ($row['actual_due_date'] ?? 'NULL') . "</td>";
    echo "<td>" . $calculatedDue . "</td>";
    echo "<td style='color: $overdueColor; font-weight: bold;'>" . $overdueStatus . "</td>";
    echo "</tr>";
}
echo "</table>";

// Count overdue books
$overdueCount = $conn->query("
    SELECT COUNT(*) AS total FROM tblborrower 
    WHERE status = 'borrowed' 
    AND COALESCE(due_date, DATE_ADD(DATE(borrow_date), INTERVAL 14 DAY)) < CURDATE()
")->fetch_assoc()['total'];

echo "<h3>Overdue Count: $overdueCount</h3>";

// Show overdue books only
echo "<h3>Overdue Books Only:</h3>";
$overdueSql = "SELECT br.borrow_id, CONCAT(s.firstname, ' ', s.lastname) as student_name, 
                      b.title as book_title, br.borrow_date, 
                      COALESCE(br.due_date, DATE_ADD(DATE(br.borrow_date), INTERVAL 14 DAY)) as due_date,
                      br.status
               FROM tblborrower br
               INNER JOIN tblstudent s ON br.student_id = s.student_id
               INNER JOIN tblbooks b ON br.book_id = b.book_id
               WHERE br.status = 'borrowed' 
               AND COALESCE(br.due_date, DATE_ADD(DATE(br.borrow_date), INTERVAL 14 DAY)) < CURDATE()
               ORDER BY COALESCE(br.due_date, DATE_ADD(DATE(br.borrow_date), INTERVAL 14 DAY)) ASC";

$overdueResult = $conn->query($overdueSql);
echo "<table border='1'>";
echo "<tr><th>Borrow ID</th><th>Student</th><th>Book</th><th>Borrow Date</th><th>Due Date</th><th>Status</th></tr>";

while ($row = $overdueResult->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['borrow_id'] . "</td>";
    echo "<td>" . $row['student_name'] . "</td>";
    echo "<td>" . $row['book_title'] . "</td>";
    echo "<td>" . $row['borrow_date'] . "</td>";
    echo "<td>" . $row['due_date'] . "</td>";
    echo "<td style='color: red; font-weight: bold;'>OVERDUE</td>";
    echo "</tr>";
}
echo "</table>";
?> 