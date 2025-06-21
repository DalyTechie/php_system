<?php
require_once 'db.php';

echo "<h2>Fixing Due Date Column</h2>";

// Check if due_date column exists
echo "<h3>Checking if due_date column exists:</h3>";
$check_column = "SHOW COLUMNS FROM tblborrower LIKE 'due_date'";
$column_result = $conn->query($check_column);

if ($column_result->num_rows == 0) {
    echo "<p>❌ due_date column does NOT exist. Adding it now...</p>";
    
    // Add the due_date column
    $add_column_sql = "ALTER TABLE tblborrower ADD COLUMN due_date DATE NOT NULL DEFAULT '2099-12-31'";
    if ($conn->query($add_column_sql)) {
        echo "<p>✅ Successfully added due_date column!</p>";
        
        // Update existing records to have proper due dates (7 days from borrow_date)
        $update_sql = "UPDATE tblborrower SET due_date = DATE_ADD(DATE(borrow_date), INTERVAL 7 DAY) WHERE due_date = '2099-12-31'";
        if ($conn->query($update_sql)) {
            echo "<p>✅ Updated existing records with proper due dates!</p>";
        } else {
            echo "<p>❌ Error updating due dates: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>❌ Error adding due_date column: " . $conn->error . "</p>";
    }
} else {
    echo "<p>✅ due_date column already exists!</p>";
}

// Show current table structure
echo "<h3>Current tblborrower Table Structure:</h3>";
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

// Show sample data
echo "<h3>Sample Borrowed Books with Due Dates:</h3>";
$sample_sql = "SELECT br.borrow_id, 
                      CONCAT(s.firstname, ' ', s.lastname) as student_name,
                      b.title as book_title,
                      br.borrow_date,
                      br.due_date,
                      br.status
               FROM tblborrower br
               INNER JOIN tblstudent s ON br.student_id = s.student_id
               INNER JOIN tblbooks b ON br.book_id = b.book_id
               WHERE br.status = 'borrowed'
               ORDER BY br.borrow_date DESC
               LIMIT 5";

$sample_result = $conn->query($sample_sql);
if ($sample_result && $sample_result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>Borrow ID</th><th>Student</th><th>Book</th><th>Borrow Date</th><th>Due Date</th><th>Status</th></tr>";
    while ($row = $sample_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['borrow_id'] . "</td>";
        echo "<td>" . $row['student_name'] . "</td>";
        echo "<td>" . $row['book_title'] . "</td>";
        echo "<td>" . $row['borrow_date'] . "</td>";
        echo "<td>" . $row['due_date'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No borrowed books found.</p>";
}

// Test overdue query
echo "<h3>Testing Overdue Query:</h3>";
$overdue_sql = "SELECT COUNT(*) AS total FROM tblborrower 
                WHERE status = 'borrowed' 
                AND due_date < CURDATE()";
$overdue_result = $conn->query($overdue_sql);
if ($overdue_result) {
    $overdue_count = $overdue_result->fetch_assoc()['total'];
    echo "<p><strong>Overdue Books Count:</strong> " . $overdue_count . "</p>";
} else {
    echo "<p><strong>Query Error:</strong> " . $conn->error . "</p>";
}
?> 