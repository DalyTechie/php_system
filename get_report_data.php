<?php
require_once 'session_check.php';
require_once 'db.php';

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';
$response = ['success' => false, 'data' => [], 'message' => '', 'count' => 0];

// Helper function to format dates
function formatDate($dateString) {
    if (empty($dateString) || $dateString === 'N/A') {
        return 'N/A';
    }
    // Convert to date only, removing time
    $date = new DateTime($dateString);
    return $date->format('Y-m-d');
}

// Helper function to determine if book is overdue
function isOverdue($dueDate) {
    if (empty($dueDate) || $dueDate === 'N/A') {
        return false;
    }
    $due = new DateTime($dueDate);
    $today = new DateTime();
    return $due < $today;
}

try {
    // First, let's check if due_date column exists
    $check_column = "SHOW COLUMNS FROM tblborrower LIKE 'due_date'";
    $column_result = $conn->query($check_column);
    $has_due_date = $column_result->num_rows > 0;
    
    switch($type) {
        case 'all':
            $sql = "SELECT book_id, title, author, isbn, category, publisher, publication_year FROM tblbooks ORDER BY title";
            $result = $conn->query($sql);
            if (!$result) {
                throw new Exception("Query failed: " . $conn->error);
            }
            $data = [];
            while($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $response['success'] = true;
            $response['data'] = $data;
            $response['count'] = count($data);
            break;
            
        case 'borrowed-month':
            $sql = "SELECT br.borrow_id, CONCAT(s.firstname, ' ', s.lastname) as student_name, 
                           b.title as book_title, br.borrow_date, 
                           DATE_ADD(DATE(br.borrow_date), INTERVAL 7 DAY) as due_date, 
                           br.status
                    FROM tblborrower br
                    INNER JOIN tblstudent s ON br.student_id = s.student_id
                    INNER JOIN tblbooks b ON br.book_id = b.book_id
                    WHERE MONTH(br.borrow_date) = MONTH(CURRENT_DATE())
                    AND YEAR(br.borrow_date) = YEAR(CURRENT_DATE())
                    ORDER BY br.borrow_date DESC";
            $result = $conn->query($sql);
            if (!$result) {
                throw new Exception("Query failed: " . $conn->error);
            }
            $data = [];
            while($row = $result->fetch_assoc()) {
                // Format dates to remove time
                $row['borrow_date'] = formatDate($row['borrow_date']);
                $row['due_date'] = formatDate($row['due_date']);
                
                // Check if overdue and update status
                if ($row['status'] === 'borrowed' && isOverdue($row['due_date'])) {
                    $row['status'] = 'overdue';
                }
                
                $data[] = $row;
            }
            $response['success'] = true;
            $response['data'] = $data;
            $response['count'] = count($data);
            break;
            
        case 'currently-borrowed':
            $sql = "SELECT br.borrow_id, CONCAT(s.firstname, ' ', s.lastname) as student_name, 
                           b.title as book_title, br.borrow_date, 
                           DATE_ADD(DATE(br.borrow_date), INTERVAL 7 DAY) as due_date, 
                           br.status
                    FROM tblborrower br
                    INNER JOIN tblstudent s ON br.student_id = s.student_id
                    INNER JOIN tblbooks b ON br.book_id = b.book_id
                    WHERE br.status = 'borrowed'
                    ORDER BY br.borrow_date DESC";
            $result = $conn->query($sql);
            if (!$result) {
                throw new Exception("Query failed: " . $conn->error);
            }
            $data = [];
            while($row = $result->fetch_assoc()) {
                // Format dates to remove time
                $row['borrow_date'] = formatDate($row['borrow_date']);
                $row['due_date'] = formatDate($row['due_date']);
                
                // Check if overdue and update status
                if ($row['status'] === 'borrowed' && isOverdue($row['due_date'])) {
                    $row['status'] = 'overdue';
                }
                
                $data[] = $row;
            }
            $response['success'] = true;
            $response['data'] = $data;
            $response['count'] = count($data);
            break;
            
        case 'overdue':
            // FIXED: Look for books with status = 'overdue'
            $sql = "SELECT br.borrow_id, CONCAT(s.firstname, ' ', s.lastname) as student_name, 
                           b.title as book_title, br.borrow_date, 
                           DATE_ADD(DATE(br.borrow_date), INTERVAL 7 DAY) as due_date, 
                           br.status
                    FROM tblborrower br
                    INNER JOIN tblstudent s ON br.student_id = s.student_id
                    INNER JOIN tblbooks b ON br.book_id = b.book_id
                    WHERE br.status = 'overdue'
                    ORDER BY br.borrow_date DESC";
            $result = $conn->query($sql);
            if (!$result) {
                throw new Exception("Query failed: " . $conn->error);
            }
            $data = [];
            while($row = $result->fetch_assoc()) {
                // Format dates to remove time
                $row['borrow_date'] = formatDate($row['borrow_date']);
                $row['due_date'] = formatDate($row['due_date']);
                
                // Keep status as overdue
                $row['status'] = 'overdue';
                
                $data[] = $row;
            }
            $response['success'] = true;
            $response['data'] = $data;
            $response['count'] = count($data);
            break;
            
        default:
            $response['message'] = 'Invalid type specified';
    }
} catch (Exception $e) {
    $response['message'] = 'Database error: ' . $e->getMessage();
}

echo json_encode($response);
?> 