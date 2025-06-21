<?php
require_once 'db.php';

echo "<h2>Final Overdue Debug</h2>";
echo "<h3>Current Date: " . date('Y-m-d') . "</h3>";

// Check table structure first
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

// Check all borrowed books with detailed date analysis
echo "<h3>All Borrowed Books with Date Analysis:</h3>";
$sql = "SELECT br.borrow_id, 
               CONCAT(s.firstname, ' ', s.lastname) as student_name,
               b.title as book_title,
               br.borrow_date,
               DATE(br.borrow_date) as borrow_date_only,
               DATE_ADD(DATE(br.borrow_date), INTERVAL 7 DAY) as due_date_7,
               DATE_ADD(DATE(br.borrow_date), INTERVAL 14 DAY) as due_date_14,
               DATE_ADD(DATE(br.borrow_date), INTERVAL 30 DAY) as due_date_30,
               CURDATE() as current_date,
               br.status
        FROM tblborrower br
        INNER JOIN tblstudent s ON br.student_id = s.student_id
        INNER JOIN tblbooks b ON br.book_id = b.book_id
        WHERE br.status = 'borrowed'
        ORDER BY br.borrow_date DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%; font-size: 12px;'>";
    echo "<tr style='background-color: #f0f0f0;'>";
    echo "<th>Borrow ID</th>";
    echo "<th>Student</th>";
    echo "<th>Book</th>";
    echo "<th>Borrow Date</th>";
    echo "<th>Borrow Date Only</th>";
    echo "<th>Due Date (7 days)</th>";
    echo "<th>Due Date (14 days)</th>";
    echo "<th>Due Date (30 days)</th>";
    echo "<th>Current Date</th>";
    echo "<th>Status</th>";
    echo "<th>7 Days Overdue?</th>";
    echo "<th>14 Days Overdue?</th>";
    echo "<th>30 Days Overdue?</th>";
    echo "</tr>";
    
    $overdue_7_count = 0;
    $overdue_14_count = 0;
    $overdue_30_count = 0;
    
    while ($row = $result->fetch_assoc()) {
        $is_overdue_7 = ($row['due_date_7'] < $row['current_date']);
        $is_overdue_14 = ($row['due_date_14'] < $row['current_date']);
        $is_overdue_30 = ($row['due_date_30'] < $row['current_date']);
        
        if ($is_overdue_7) $overdue_7_count++;
        if ($is_overdue_14) $overdue_14_count++;
        if ($is_overdue_30) $overdue_30_count++;
        
        $row_color = ($is_overdue_7 || $is_overdue_14 || $is_overdue_30) ? '#ffebee' : '#f8f9fa';
        echo "<tr style='background-color: $row_color;'>";
        echo "<td>" . $row['borrow_id'] . "</td>";
        echo "<td>" . $row['student_name'] . "</td>";
        echo "<td>" . $row['book_title'] . "</td>";
        echo "<td>" . $row['borrow_date'] . "</td>";
        echo "<td>" . $row['borrow_date_only'] . "</td>";
        echo "<td>" . $row['due_date_7'] . "</td>";
        echo "<td>" . $row['due_date_14'] . "</td>";
        echo "<td>" . $row['due_date_30'] . "</td>";
        echo "<td>" . $row['current_date'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "<td style='color: " . ($is_overdue_7 ? 'red' : 'green') . "; font-weight: bold;'>" . ($is_overdue_7 ? 'YES' : 'NO') . "</td>";
        echo "<td style='color: " . ($is_overdue_14 ? 'red' : 'green') . "; font-weight: bold;'>" . ($is_overdue_14 ? 'YES' : 'NO') . "</td>";
        echo "<td style='color: " . ($is_overdue_30 ? 'red' : 'green') . "; font-weight: bold;'>" . ($is_overdue_30 ? 'YES' : 'NO') . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>Summary:</h3>";
    echo "<p><strong>Total Borrowed Books:</strong> " . $result->num_rows . "</p>";
    echo "<p><strong>Overdue (7 days):</strong> " . $overdue_7_count . "</p>";
    echo "<p><strong>Overdue (14 days):</strong> " . $overdue_14_count . "</p>";
    echo "<p><strong>Overdue (30 days):</strong> " . $overdue_30_count . "</p>";
    
} else {
    echo "<p>No borrowed books found.</p>";
}

// Test the exact query from Report.php
echo "<h3>Testing Report.php Query (30 days):</h3>";
$overdue_query = "SELECT COUNT(*) AS total FROM tblborrower 
                  WHERE status = 'borrowed' 
                  AND DATE_ADD(DATE(borrow_date), INTERVAL 30 DAY) < CURDATE()";

$overdue_result = $conn->query($overdue_query);
if ($overdue_result) {
    $overdue_count = $overdue_result->fetch_assoc()['total'];
    echo "<p><strong>Overdue Count from Report.php Query:</strong> " . $overdue_count . "</p>";
} else {
    echo "<p><strong>Query Error:</strong> " . $conn->error . "</p>";
}

// Check if there are any books with status = 'overdue'
echo "<h3>Books with status = 'overdue':</h3>";
$status_overdue_query = "SELECT COUNT(*) AS total FROM tblborrower WHERE status = 'overdue'";
$status_overdue_result = $conn->query($status_overdue_query);
if ($status_overdue_result) {
    $status_overdue_count = $status_overdue_result->fetch_assoc()['total'];
    echo "<p><strong>Books with status = 'overdue':</strong> " . $status_overdue_count . "</p>";
} else {
    echo "<p><strong>Query Error:</strong> " . $conn->error . "</p>";
}
?> 