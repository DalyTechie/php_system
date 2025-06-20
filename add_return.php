<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $book_id = $_POST['book_id'];
    $return_date = $_POST['return_date'];
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
        header('Location: Return.php?msg=Book returned successfully');
        exit();
    } else {
        header('Location: Return.php?error=No borrowed record found');
        exit();
    }
}
header('Location: Return.php');
exit(); 