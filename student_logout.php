<?php
include("db.php");
session_start();
date_default_timezone_set('Asia/Kolkata');

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    // Update student's login status
    $updateStatus = "UPDATE students SET logged_in = 0 WHERE username = '$username'";
    mysqli_query($conn, $updateStatus);

    if (isset($_SESSION['prno'])) {
        $prno = $_SESSION['prno'];

        // Set logout_time only where it's not already set
        $updateQuery = "UPDATE student_forms 
                        SET logout_time = NOW() 
                        WHERE prno = '$prno' 
                        AND logout_time IS NULL 
                        ORDER BY login_time DESC 
                        LIMIT 1";
        mysqli_query($conn, $updateQuery);
    }
}

// Destroy session
session_destroy();

// Redirect to login
header("Location: student_login.php");
exit();
?>
