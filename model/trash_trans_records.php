<?php
include '../server/server.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $selectSql = "SELECT * FROM tblpayments WHERE id = ?";
    $selectStmt = $conn->prepare($selectSql);
    $selectStmt->bind_param("i", $id);
    $selectStmt->execute();
    $paymentData = $selectStmt->get_result()->fetch_assoc();

    $insertSql = "INSERT INTO tbl_trash_trans (`trans_id`, `details`, `amounts`, `user`, `name`, `email`, `status`, `requirement`) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("ssssssss",  
        $paymentData['trans_id'], 
        $paymentData['details'], 
        $paymentData['amounts'], 
        $paymentData['user'], 
        $paymentData['name'],
        $paymentData['email'],
        $paymentData['status'],
        $paymentData['requirement']);
    $insertStmt->execute();

    $deleteSql = "DELETE FROM tblpayments WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $id);
    $deleteStmt->execute();

    $_SESSION['message'] = "Payment records have been removed successfully.";
    $_SESSION['success'] = "success";

    header("Location: ../transaction_reports.php");
    exit;
}
?>
