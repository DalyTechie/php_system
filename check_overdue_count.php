<?php
require_once 'db.php';

echo "<h2>Overdue Books Debug</h2>";
echo "<h3>Current Date: " . date('Y-m-d') . "</h3>";

// Check all borrowed books with their due dates
echo "<h3>All Borrowed Books with Due Dates:</h3>";
$sql = "SELECT br.borrow_id, 
               CONCAT(s.firstname, ' ', s.lastname) as student_name,
               b.title as book_title,
               br.borrow_date,
               COALESCE(br.return_date, DATE_ADD(DATE(br.borrow_date), INTERVAL 7 DAY)) as calculated_due_date,
               br.status,
               CASE 
                   WHEN br.status = 'borrowed' AND COALESCE(br.return_date, DATE_ADD(DATE(br.borrow_date), INTERVAL 7 DAY)) < CURDATE() 
                   THEN 'OVERDUE'
                   ELSE 'NOT OVERDUE'
               END as overdue_status
        FROM tblborrower br
        INNER JOIN tblstudent s ON br.student_id = s.student_id
        INNER JOIN tblbooks b ON br.book_id = b.book_id
        WHERE br.status = 'borrowed'
        ORDER BY br.borrow_date DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background-color: #f0f0f0;'>";
    echo "<th>Borrow ID</th>";
    echo "<th>Student Name</th>";
    echo "<th>Book Title</th>";
    echo "<th>Borrow Date</th>";
    echo "<th>Calculated Due Date</th>";
    echo "<th>Status</th>";
    echo "<th>Overdue Status</th>";
    echo "</tr>";
    
    $overdue_count = 0;
    while ($row = $result->fetch_assoc()) {
        $is_overdue = ($row['overdue_status'] === 'OVERDUE');
        if ($is_overdue) {
            $overdue_count++;
        }
        
        $row_color = $is_overdue ? '#ffebee' : '#f8f9fa';
        echo "<tr style='background-color: $row_color;'>";
        echo "<td>" . $row['borrow_id'] . "</td>";
        echo "<td>" . $row['student_name'] . "</td>";
        echo "<td>" . $row['book_title'] . "</td>";
        echo "<td>" . $row['borrow_date'] . "</td>";
        echo "<td>" . $row['calculated_due_date'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "<td style='color: " . ($is_overdue ? 'red' : 'green') . "; font-weight: bold;'>" . $row['overdue_status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>Summary:</h3>";
    echo "<p><strong>Total Borrowed Books:</strong> " . $result->num_rows . "</p>";
    echo "<p><strong>Overdue Books:</strong> " . $overdue_count . "</p>";
    echo "<p><strong>Not Overdue:</strong> " . ($result->num_rows - $overdue_count) . "</p>";
    
} else {
    echo "<p>No borrowed books found.</p>";
}

// Check the current overdue query from Report.php
echo "<h3>Current Overdue Query Result:</h3>";
$overdue_query = "SELECT COUNT(*) AS total FROM tblborrower 
                  WHERE status = 'borrowed' 
                  AND COALESCE(return_date, DATE_ADD(DATE(borrow_date), INTERVAL 7 DAY)) < CURDATE()";

$overdue_result = $conn->query($overdue_query);
if ($overdue_result) {
    $overdue_count = $overdue_result->fetch_assoc()['total'];
    echo "<p><strong>Overdue Count from Query:</strong> " . $overdue_count . "</p>";
} else {
    echo "<p><strong>Query Error:</strong> " . $conn->error . "</p>";
}
?> 