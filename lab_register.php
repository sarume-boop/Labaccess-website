<?php
include("db.php");

// Check if lab assistant already exists
$check = mysqli_query($conn, "SELECT * FROM lab_assistant");
if (mysqli_num_rows($check) > 0) {
    echo "<script>alert('Lab assistant already registered. Please login.'); window.location.href='assistant_login.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Register only if table is empty
    $insert = mysqli_query($conn, "INSERT INTO lab_assistant (username, password) VALUES ('$username', '$password')");

    if ($insert) {
        echo "<script>alert('Registration successful! Please login.'); window.location.href='assistant_login.php';</script>";
    } else {
        echo "<script>alert('Something went wrong!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Assistant Registration</title>
    <style>
        body {
            background: linear-gradient(to right, #fbd3e9, #bb377d);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-box {
            background-color: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            width: 350px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"], input[type="password"] {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 14px;
        }

        input[type="submit"] {
            width: 95%;
            padding: 12px;
            background-color: #e91e63;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 15px;
        }

        input[type="submit"]:hover {
            background-color: #d81b60;
        }
    </style>
</head>
<body>

<div class="register-box">
    <h2>Lab Assistant Registration</h2>

    <form method="POST" action="">
        <input type="text" name="username" placeholder="Enter Username" required><br>
        <input type="password" name="password" placeholder="Enter Password" required><br>
        <input type="submit" value="Register">
    </form>
</div>

</body>
</html>
