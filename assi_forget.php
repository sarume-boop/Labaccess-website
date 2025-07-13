<?php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];

    // Check if lab assistant exists using prepared statement
    $stmt = $conn->prepare("SELECT * FROM lab_assistants WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update password
        $update_stmt = $conn->prepare("UPDATE lab_assistants SET password = ? WHERE username = ?");
        $update_stmt->bind_param("ss", $hashed_password, $username);
        $update_stmt->execute();

        echo "<script>alert('Password updated successfully!'); window.location.href='labassistant_login.php';</script>";
        exit();
    } else {
        echo "<script>alert('Username not found!'); window.location.href='labassistant_forgot_password.php';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lab Assistant - Forgot Password</title>
    <style>
        body {
            background: linear-gradient(to right, #36D1DC, #5B86E5);
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
            color: #36D1DC;
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
            background: #36D1DC;
            color: white;
            border: none;
            padding: 10px;
            margin-top: 20px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
        }
        .box button:hover {
            background: #2e9cca;
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
