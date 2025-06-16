<?php
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $category = $_POST['category'];
    
    // Generate a unique book ID (you can modify this format as needed)
    $book_id = 'BK' . date('Y') . rand(1000, 9999);
    
    // Prepare the SQL statement
    $sql = "INSERT INTO tblbooks (book_id, title, author, publisher, category) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $book_id, $title, $author, $publisher, $category);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>
            alert('Book added successfully!');
            window.location.href = '../Add_Books.php';
        </script>";
    } else {
        echo "<script>
            alert('Error adding book: " . $conn->error . "');
            window.location.href = '../Add_Books.php';
        </script>";
    }
    
    $stmt->close();
} else {
    // If not a POST request, redirect to Add_Books.php
    header("Location: ../Add_Books.php");
    exit();
}

$conn->close();
?>
