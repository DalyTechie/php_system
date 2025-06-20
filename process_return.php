<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add_return') {
        // Add a return: set status to 'returned' and update return_date
        $student_id = $_POST['student_id'];
        $book_id = $_POST['book_id'];
        $return_date = $_POST['return_date'];
        
        // Find the borrow record
        $sql = "SELECT borrow_id FROM tblborrower WHERE student_id=? AND book_id=? AND status='borrowed' ORDER BY borrow_date DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $student_id, $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $borrow_id = $row['borrow_id'];
            $update = $conn->prepare("UPDATE tblborrower SET status='returned', return_date=? WHERE borrow_id=?");
            $update->bind_param('si', $return_date, $borrow_id);
            $update->execute();
            $_SESSION['msg'] = 'Book returned successfully';
            header('Location: Return.php');
            exit();
        } else {
            $_SESSION['error'] = 'No borrowed record found';
            header('Location: Return.php');
            exit();
        }
    } elseif ($action === 'update_return') {
        // Update a return record
        $borrow_id = $_POST['borrow_id'];
        $return_date = $_POST['return_date'];
        $update = $conn->prepare("UPDATE tblborrower SET return_date=? WHERE borrow_id=?");
        $update->bind_param('si', $return_date, $borrow_id);
        $update->execute();
    
        header('Location: Return.php');
        exit();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'delete_return') {
    // Delete a return record
    $borrow_id = $_GET['borrow_id'];
    $delete = $conn->prepare("DELETE FROM tblborrower WHERE borrow_id=?");
    $delete->bind_param('i', $borrow_id);
    $delete->execute();
 
    header('Location: Return.php');
    exit();
}

header('Location: Return.php');
exit(); 