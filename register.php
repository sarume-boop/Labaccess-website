<?php
include("db.php");
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Check if username already exists
    $check = "SELECT * FROM students WHERE username = '$username'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {
        $error = "Username already registered. Try another.";
    } else {
        $query = "INSERT INTO students (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($conn, $query)) {
            $success = "Registration successful! You can now login.";
        } else {
            $error = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Student Registration</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    * {
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    body {
      min-height: 100vh;
      margin: 0;
      /* Pinkish gradient background */
      background: linear-gradient(135deg,rgb(243, 255, 154) 0%,rgb(142, 113, 245) 100%);
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .container {
      background: rgba(255, 182, 193, 0.26); /* Pinkish glassmorphism */
      border-radius: 24px;
      box-shadow: 0 8px 32px 0 rgba(255, 105, 180, 0.18);
      backdrop-filter: blur(14px) saturate(160%);
      -webkit-backdrop-filter: blur(14px) saturate(160%);
      border: 1.5px solid rgba(255,182,193,0.25);
      padding: 28px 20px 20px 20px;
      width: 310px;
      max-width: 92vw;
      text-align: center;
      transition: box-shadow 0.3s;
      position: relative;
      overflow: hidden;
    }
    .container::before {
      content: '';
      position: absolute;
      top: -50px; left: -50px;
      width: 120px; height: 120px;
      background: rgba(255,182,193,0.23);
      border-radius: 50%;
      z-index: 0;
      filter: blur(10px);
    }
    .container h2 {
      margin-bottom: 22px;
      color:rgb(116, 84, 231);
      font-weight: 700;
      letter-spacing: 1px;
      font-size: 1.5rem;
      position: relative;
      z-index: 1;
    }
    .error, .success {
      margin-bottom: 15px;
      font-weight: 600;
      font-size: 1rem;
      border-radius: 6px;
      padding: 7px 0;
      width: 100%;
      display: block;
      position: relative;
      z-index: 1;
    }
    .error {background: #ffe5ec; color:rgb(135, 194, 24);}
    .success {background: #e6ffe5; color:rgb(75, 67, 160);}
    .form-group {
      position: relative;
      margin-bottom: 18px;
      z-index: 1;
    }
    .form-group i {
      position: absolute;
      left: 12px;
      top: 50%;
      transform: translateY(-50%);
      color: #e75480;
      font-size: 1rem;
      opacity: 0.8;
    }
    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 9px 12px 9px 36px;
      border: 1.5px solidrgb(187, 226, 248);
      border-radius: 8px;
      background: rgba(255,255,255,0.68);
      font-size: 15px;
      margin: 0;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
      box-shadow: 0 1px 3px rgba(255,182,193,0.06);
    }
    input[type="text"]:focus,
    input[type="password"]:focus {
      border-color: #ff9a9e;
      box-shadow: 0 2px 8px #ff9a9e44;
      background: rgba(255,255,255,0.92);
    }
    input[type="submit"] {
      width: 100%;
      background: linear-gradient(90deg,rgb(247, 154, 255) 0%, #fad0c4 100%);
      color: #fff;
      padding: 10px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      margin-top: 5px;
      font-weight: 600;
      letter-spacing: 1px;
      cursor: pointer;
      box-shadow: 0 2px 8px #ff9a9e22;
      transition: background 0.3s, box-shadow 0.2s;
    }
    input[type="submit"]:hover {
      background: linear-gradient(90deg, #fad0c4 0%, #ff9a9e 100%);
      box-shadow: 0 4px 16px #ff9a9e33;
    }
    .login-link {
      margin-top: 18px;
      font-size: 14px;
      z-index: 1;
      position: relative;
    }
    .login-link a {
      color:rgb(231, 101, 84);
      font-weight: bold;
      text-decoration: none;
      transition: color 0.2s;
    }
    .login-link a:hover {
      color:rgb(205, 255, 154);
      text-decoration: underline;
    }
    @media (max-width: 400px) {
      .container {padding: 16px 5vw;}
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Student Registration</h2>
    <?php if ($error): ?>
      <div class="error"><?php echo $error; ?></div>
    <?php elseif ($success): ?>
      <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    <form method="post" autocomplete="off">
      <div class="form-group">
        <i class="fa fa-user"></i>
        <input type="text" name="username" placeholder="Create Username" required>
      </div>
      <div class="form-group">
        <i class="fa fa-lock"></i>
        <input type="password" name="password" placeholder="Create Password" required>
      </div>
      <input type="submit" value="Register">
    </form>
    <div class="login-link">
      Already registered? <a href="student_login.php">Login here</a>
    </div>
  </div>
</body>
</html>
