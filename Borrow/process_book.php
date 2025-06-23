<?php
require_once '../config/database.php';

header('Content-Type: application/json');

try {
    // Validate input
    if (empty($_POST['title']) || empty($_POST['author']) || empty($_POST['isbn'])) {
        throw new Exception('All fields are required');
    }

    // Sanitize input
    $title = htmlspecialchars(trim($_POST['title']));
    $author = htmlspecialchars(trim($_POST['author']));
    $isbn = htmlspecialchars(trim($_POST['isbn']));
    $description = htmlspecialchars(trim($_POST['description'] ?? ''));
    $quantity = (int)($_POST['quantity'] ?? 1);

    // Validate ISBN format (basic validation)
    if (!preg_match('/^[0-9-]{10,13}$/', $isbn)) {
        throw new Exception('Invalid ISBN format');
    }

    // Check if book already exists
    $stmt = $conn->prepare("SELECT id FROM books WHERE isbn = ?");
    $stmt->execute([$isbn]);
    if ($stmt->rowCount() > 0) {
        throw new Exception('A book with this ISBN already exists');
    }

    // Insert new book
    $stmt = $conn->prepare("
        INSERT INTO books (title, author, isbn, description, quantity, available_quantity, created_at)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
    ");

    $stmt->execute([
        $title,
        $author,
        $isbn,
        $description,
        $quantity,
        $quantity // Initially, all books are available
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Book added successfully'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 