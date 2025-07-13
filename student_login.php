<?php
include("db.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Secure query to avoid SQL injection
    $query = "SELECT * FROM students WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Successful login - set session and update login status/time
        $_SESSION['username'] = $username;
        $_SESSION['prno'] = $row['prno']; // Save PRN for logout use

        $update = "UPDATE students SET logged_in = 1, login_time = NOW() WHERE username = ?";
        $stmt2 = mysqli_prepare($conn, $update);
        mysqli_stmt_bind_param($stmt2, "s", $username);
        mysqli_stmt_execute($stmt2);

        // Redirect to dashboard
        header("Location: form_student.php");
        exit();
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid username or password!'); window.location.href='student_login.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;400&family=Roboto:wght@400;300&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Roboto', sans-serif;
            overflow: hidden;
        }
        .background-svg {
            position: absolute;
            z-index: 0;
            top: 0; left: 0; width: 100vw; height: 100vh;
            pointer-events: none;
            opacity: 0.14;
        }
        .login-box {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.82);
            border-radius: 28px;
            box-shadow: 0 12px 48px 0 rgba(70,70,120,0.18), 0 1.5px 8px rgba(30,60,114,0.10), 0 0 32px 0 #43cea288;
            padding: 44px 34px 34px 34px;
            width: 370px;
            max-width: 92vw;
            backdrop-filter: blur(16px) saturate(170%);
            border: 3px solid transparent;
            text-align: center;
            animation: appear 1.2s cubic-bezier(.5,1.5,.5,1);
            margin: 40px 0;
            background-clip: padding-box, border-box;
            background-origin: padding-box, border-box;
        }
        .login-box::before {
            content: '';
            position: absolute;
            inset: -3px;
            z-index: -1;
            border-radius: 32px;
            background: linear-gradient(120deg, #43cea2, #fed6e3, #fcb69f, #185a9d 90%);
            background-size: 200% 200%;
            filter: blur(1.5px);
            animation: borderMove 6s linear infinite;
        }
        @keyframes borderMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes appear {
            from { opacity: 0; transform: translateY(40px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .login-logo {
            font-size: 2.8rem;
            margin-bottom: 10px;
            animation: float 2.5s ease-in-out infinite;
            filter: drop-shadow(0 3px 14px #43cea288);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0);}
            50% { transform: translateY(-10px);}
        }
        h2 {
            font-family: 'Montserrat', sans-serif;
            color: #1e3c72;
            font-size: 2rem;
            margin-bottom: 8px;
            font-weight: 700;
            letter-spacing: 1px;
            text-shadow: 0 2px 6px #fed6e366;
        }
        .login-box input {
            width: 100%;
            padding: 13px 12px;
            margin-top: 16px;
            border: none;
            border-radius: 10px;
            background: rgba(168,237,234,0.11);
            font-size: 1.08rem;
            font-family: 'Roboto', sans-serif;
            color: #1e3c72;
            box-shadow: 0 2px 8px #a8edea22;
            outline: 2px solid transparent;
            transition: outline 0.2s, box-shadow 0.2s;
        }
        .login-box input:focus {
            outline: 2.5px solid #43cea2;
            box-shadow: 0 0 0 3px #fed6e344;
            background: #fff;
        }
        .login-box button {
            width: 100%;
            background: linear-gradient(90deg, #43cea2 0%, #185a9d 100%);
            color: #fff;
            border: none;
            padding: 13px 0;
            margin-top: 28px;
            border-radius: 12px;
            font-size: 1.08rem;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 12px #43cea244;
            transition: background 0.23s, transform 0.15s;
        }
        .login-box button:hover {
            background: linear-gradient(90deg, #185a9d 0%, #43cea2 100%);
            transform: translateY(-2px) scale(1.045);
        }
        .login-box .link {
            text-align: center;
            margin-top: 16px;
        }
        .login-box .link a {
            color: #43cea2;
            text-decoration: underline;
            font-weight: 500;
            transition: color 0.2s;
        }
        .login-box .link a:hover {
            color: #185a9d;
        }
        @media (max-width: 600px) {
            .login-box { padding: 18px 4vw 18px 4vw; width: 97vw;}
            h2 { font-size: 1.2rem; }
            .login-logo { font-size: 2rem; }
        }
    </style>
</head>
<body>
    <!-- Pastel abstract SVG background -->
    <svg class="background-svg" viewBox="0 0 1440 900" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="300" cy="200" r="260" fill="#a8edea"/>
        <circle cx="1200" cy="700" r="320" fill="#fed6e3"/>
        <circle cx="1100" cy="150" r="130" fill="#fcb69f"/>
        <circle cx="400" cy="800" r="140" fill="#43cea2"/>
    </svg>
    <form class="login-box" method="POST" action="">
        <div class="login-logo" title="Student Login">👨‍🎓</div>
        <h2>Student Login</h2>
        <input type="text" name="username" placeholder="Enter username" required autocomplete="username">
        <input type="password" name="password" placeholder="Enter password" required autocomplete="current-password">
        <div class="link">
            <p><a href="forgot_password.php">Forgot Password?</a></p>
        </div>
        <button type="submit">Login</button>
        <div class="link">
            <p>Not registered? <a href="register.php">Register here</a></p>
        </div>
    </form>
</body>
</html>
