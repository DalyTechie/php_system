<?php
require_once 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $borrow_id = $_POST['borrow_id'];
    $return_date = $_POST['return_date'];
    $update = $conn->prepare("UPDATE tblborrower SET return_date=? WHERE borrow_id=?");
    $update->bind_param('si', $return_date, $borrow_id);
    $update->execute();
    header('Location: Return.php?');
    exit();
}
header('Location: Return.php');
exit(); 