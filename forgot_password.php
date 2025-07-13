<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];

    // Check if user exists
    $check_query = "SELECT * FROM students WHERE username = '$username'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) == 1) {
        // Update password
        $update_query = "UPDATE students SET password = '$new_password' WHERE username = '$username'";
        mysqli_query($conn, $update_query);

        echo "<script>alert('Password updated successfully!'); window.location.href='student_login.php';</script>";
        exit();
    } else {
        echo "<script>alert('Username not found!'); window.location.href='forgot_password.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            background: linear-gradient(to right, #f2709c, #ff9472);
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            width: 350px;
        }
        .box h2 {
            text-align: center;
            color: #f2709c;
        }
        .box input {
            width: 100%;
            padding: 10px;
            margin-top: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .box button {
            width: 100%;
            background: #f2709c;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 20px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        .box button:hover {
            background: #e75480;
        }
    </style>
</head>
<body>
    <form class="box" method="POST">
        <h2>Reset Password</h2>
        <input type="text" name="username" placeholder="Enter your username" required>
        <input type="password" name="new_password" placeholder="Enter new password" required>
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>