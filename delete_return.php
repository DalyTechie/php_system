<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'db.php';

$borrow_id = $_GET['borrow_id'] ?? $_POST['borrow_id'] ?? null;

if ($borrow_id && is_numeric($borrow_id) && intval($borrow_id) > 0) {
    $borrow_id = intval($borrow_id);
    $delete = $conn->prepare("DELETE FROM tblborrower WHERE borrow_id=?");
    if ($delete) {
        $delete->bind_param('i', $borrow_id);
        if ($delete->execute()) {
            header('Location: Return.php?');
            exit();
        } else {
            // Database execution error
            header('Location: Return.php?error=Failed to delete record: ' . $delete->error);
            exit();
        }
    } else {
        // SQL prepare error
        header('Location: Return.php?error=Failed to prepare delete statement: ' . $conn->error);
        exit();
    }
} else {
    header('Location: Return.php?error=Invalid or missing borrow_id');
    exit();
} 