<?php
include '../server/server.php';

$username = $conn->real_escape_string($_POST['fullname']);
$cur_pass = $conn->real_escape_string($_POST['current_pass']);
$new_pass = $conn->real_escape_string($_POST['newest_pass']);
$con_pass = $conn->real_escape_string($_POST['confirm_pass']);

if (!empty($username)) {
    if ($new_pass == $con_pass) {
        $check = "SELECT * FROM tbl_user_resident WHERE fullname='$username'";
        $res = $conn->query($check);

        if ($res->num_rows) {
            $user = $res->fetch_assoc();
            $stored_hash = $user['password'];
            if (password_verify($cur_pass, $stored_hash)) {
                $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);

                $query = "UPDATE tbl_user_resident SET `password`='$new_hash' WHERE fullname='$username'";
                $result = $conn->query($query);

                if ($result === true) {
                    $_SESSION['message'] = 'Password has been updated!';
                    $_SESSION['success'] = 'success';
                } else {
                    $_SESSION['message'] = 'Something went wrong!';
                    $_SESSION['success'] = 'danger';
                }
            } else {
                $_SESSION['message'] = 'Current Password is incorrect!';
                $_SESSION['success'] = 'danger';
            }
        } else {
            $_SESSION['message'] = 'No Username found!';
            $_SESSION['success'] = 'danger';
        }
    } else {
        $_SESSION['message'] = 'Password did not match!';
        $_SESSION['success'] = 'danger';
    }
} else {
    $_SESSION['message'] = 'No Username found!';
    $_SESSION['success'] = 'danger';
}

if (isset($_SERVER["HTTP_REFERER"])) {
    header("Location: " . $_SERVER["HTTP_REFERER"]);
} else {
    header("Location: ../login.php");
}

$conn->close();
?>
