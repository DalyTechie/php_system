<?php
require_once '../db.php';

if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    
    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT * FROM tblbooks WHERE book_id = ?");
    $stmt->bind_param("s", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        // Return book data as JSON
        header('Content-Type: application/json');
        echo json_encode($row);
    } else {
        // Book not found
        http_response_code(404);
        echo json_encode(['error' => 'Book not found']);
    }
    
    $stmt->close();
} else {
    // No book ID provided
    http_response_code(400);
    echo json_encode(['error' => 'No book ID provided']);
}

$conn->close();
?> 