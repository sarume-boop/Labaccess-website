<?php
session_start();
include("db.php");

if (isset($_SESSION['student_id'])) {
    $student_id = $_SESSION['student_id'];
    $logout_time = date("Y-m-d H:i:s");

    // Update the logout time in the database
    $query = "UPDATE student_forms SET logout_time = ? WHERE student_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $logout_time, $student_id);
    $stmt->execute();
    $stmt->close();

    // Destroy the session and redirect to the login page
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
