<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'session_check.php';
require_once 'db.php';

header('Content-Type: application/json');

// Log the request
error_log("Received request for student details. GET params: " . print_r($_GET, true));

if (!isset($_GET['student_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Student ID is required']);
    exit;
}

$student_id = $_GET['student_id'];
error_log("Processing request for student_id: " . $student_id);

try {
    $sql = "SELECT s.*, c.course_name,
               (SELECT COUNT(*) FROM tblborrower b 
                WHERE b.student_id = s.student_id AND b.return_date IS NULL) as active_borrows
            FROM tblstudent s
            LEFT JOIN tblcourse c ON s.course_id = c.course_id
            WHERE s.student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        error_log("No student found with ID: " . $student_id);
        http_response_code(404);
        echo json_encode(['error' => 'Student not found']);
        exit;
    }

    // Ensure photo path is complete
    if ($student['photo'] && !preg_match('/^(https?:\\/\\/|uploads\\/)/', $student['photo'])) {
        $student['photo'] = 'uploads/students/' . $student['photo'];
    }

    // Format dates
    $student['created_at'] = date('Y-m-d H:i:s', strtotime($student['created_at']));
    
    // Handle null values
    $student['email'] = $student['email'] ?? '';
    $student['phone'] = $student['phone'] ?? '';
    $student['address'] = $student['address'] ?? '';
    
    error_log("Sending student data: " . print_r($student, true));
    echo json_encode($student);

} catch(PDOException $e) {
    error_log("Error in get_student_details.php: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?> 