<?php
include '../server/server.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $selectSql = "SELECT * FROM tblresident_requested WHERE cert_id = ?";
    $selectStmt = $conn->prepare($selectSql);
    $selectStmt->bind_param("i", $id);
    $selectStmt->execute();
    $residentData = $selectStmt->get_result()->fetch_assoc();

    $insertSql = "INSERT INTO tbl_trash_reqcert (`req_cert_id`, `resident_name`, `certificate_name`, `purok`, `email`, `requirement`, `status`) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("sssssss",  
        $residentData['req_cert_id'], 
        $residentData['resident_name'], 
        $residentData['certificate_name'], 
        $residentData['purok'], 
        $residentData['email'],
        $residentData['requirement'],
        $residentData['status']);
    $insertStmt->execute();

    $deleteSql = "DELETE FROM tblresident_requested WHERE cert_id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $id);
    $deleteStmt->execute();

    $_SESSION['message'] = "Resident records have been removed successfully.";
    $_SESSION['success'] = "success";

    header("Location: ../certificates_reports.php");
    exit;
}
?>
