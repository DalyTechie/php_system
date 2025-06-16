<?php
require_once '../db.php';

// Check if book_id is provided
if (isset($_GET['book_id'])) {
    $book_id = $_GET['book_id'];
    
    // First, check if the book exists
    $check_sql = "SELECT * FROM tblbooks WHERE book_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $book_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Book exists, proceed with deletion
        $delete_sql = "DELETE FROM tblbooks WHERE book_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("s", $book_id);
        
        if ($delete_stmt->execute()) {
            // Deletion successful
            echo "<script>
                alert('Book deleted successfully!');
                window.location.href = '../Add_Books.php';
            </script>";
        } else {
            // Error in deletion
            echo "<script>
                alert('Error deleting book: " . $conn->error . "');
                window.location.href = '../Add_Books.php';
            </script>";
        }
        $delete_stmt->close();
    } else {
        // Book not found
        echo "<script>
            alert('Book not found!');
            window.location.href = '../Add_Books.php';
        </script>";
    }
    $check_stmt->close();
} else {
    // No book_id provided
    echo "<script>
        alert('No book ID provided!');
        window.location.href = '../Add_Books.php';
    </script>";
}

$conn->close();
?>