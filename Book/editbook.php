<?php
require_once '../db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $category = $_POST['category'];
    
    // Prepare the SQL statement
    $sql = "UPDATE tblbooks SET title = ?, author = ?, publisher = ?, category = ? WHERE book_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $title, $author, $publisher, $category, $book_id);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>
            alert('Book updated successfully!');
            window.location.href = '../Add_Books.php';
        </script>";
    } else {
        echo "<script>
            alert('Error updating book: " . $conn->error . "');
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