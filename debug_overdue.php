<?php
require_once 'db.php';

echo "<h2>Overdue Books Debug</h2>";
echo "<h3>Current Date: " . date('Y-m-d') . "</h3>";

// Check table structure
echo "<h3>tblborrower Table Structure:</h3>";
$describe_sql = "DESCRIBE tblborrower";
$describe_result = $conn->query($describe_sql);
echo "<table border='1'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
while ($row = $describe_result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . $row['Key'] . "</td>";
    echo "<td>" . $row['Default'] . "</td>";
    echo "<td>" . $row['Extra'] . "</td>";
    echo "</tr>";
}
echo "</table>";

// Check all borrowed books
echo "<h3>All Borrowed Books:</h3>";
$sql = "SELECT br.borrow_id, 
               CONCAT(s.firstname, ' ', s.lastname) as student_name,
               b.title as book_title,
               br.borrow_date,
               br.due_date,
               br.return_date,
               br.status,
               CURDATE() as current_date
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
    echo "<th>Due Date</th>";
    echo "<th>Return Date</th>";
    echo "<th>Status</th>";
    echo "<th>Current Date</th>";
    echo "<th>Is Overdue?</th>";
    echo "</tr>";
    
    $overdue_count = 0;
    while ($row = $result->fetch_assoc()) {
        $is_overdue = ($row['due_date'] < $row['current_date']);
        
        if ($is_overdue) {
            $overdue_count++;
        }
        
        $row_color = $is_overdue ? '#ffebee' : '#f8f9fa';
        echo "<tr style='background-color: $row_color;'>";
        echo "<td>" . $row['borrow_id'] . "</td>";
        echo "<td>" . $row['student_name'] . "</td>";
        echo "<td>" . $row['book_title'] . "</td>";
        echo "<td>" . $row['borrow_date'] . "</td>";
        echo "<td>" . $row['due_date'] . "</td>";
        echo "<td>" . ($row['return_date'] ? $row['return_date'] : 'NULL') . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "<td>" . $row['current_date'] . "</td>";
        echo "<td style='color: " . ($is_overdue ? 'red' : 'green') . "; font-weight: bold;'>" . ($is_overdue ? 'YES' : 'NO') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>Summary:</h3>";
    echo "<p><strong>Total Borrowed Books:</strong> " . $result->num_rows . "</p>";
    echo "<p><strong>Overdue Books:</strong> " . $overdue_count . "</p>";
    
} else {
    echo "<p>No borrowed books found.</p>";
}

// Test the exact query from Report.php
echo "<h3>Testing Report.php Query:</h3>";
$overdue_query = "SELECT COUNT(*) AS total FROM tblborrower 
                  WHERE status = 'borrowed' 
                  AND due_date < CURDATE()";

$overdue_result = $conn->query($overdue_query);
if ($overdue_result) {
    $overdue_count = $overdue_result->fetch_assoc()['total'];
    echo "<p><strong>Overdue Count from Report.php Query:</strong> " . $overdue_count . "</p>";
} else {
    echo "<p><strong>Query Error:</strong> " . $conn->error . "</p>";
}

// Check if there are any books with due_date in the past
echo "<h3>Books with Past Due Dates:</h3>";
$past_due_query = "SELECT br.borrow_id, b.title, br.due_date, CURDATE() as current_date
                   FROM tblborrower br
                   INNER JOIN tblbooks b ON br.book_id = b.book_id
                   WHERE br.due_date < CURDATE()
                   ORDER BY br.due_date";

$past_due_result = $conn->query($past_due_query);
if ($past_due_result && $past_due_result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Borrow ID</th><th>Book Title</th><th>Due Date</th><th>Current Date</th></tr>";
    while ($row = $past_due_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['borrow_id'] . "</td>";
        echo "<td>" . $row['title'] . "</td>";
        echo "<td>" . $row['due_date'] . "</td>";
        echo "<td>" . $row['current_date'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No books with past due dates found.</p>";
}
?> 