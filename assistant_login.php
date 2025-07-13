<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM lab_assistant WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['assistant_logged_in'] = true;
        header("Location: dashboard_assistant.php");
        exit();
    } else {
        echo "<script>alert('Invalid login credentials');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Assistant Login</title>
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
        .login-container {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.88);
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
        .login-container::before {
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
            margin-bottom: 12px;
            display: flex;
            justify-content: center;
        }
        .login-logo svg {
            width: 58px;
            height: 58px;
            filter: drop-shadow(0 3px 14px #43cea288);
            animation: float 2.5s ease-in-out infinite;
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
        input[type="text"], input[type="password"] {
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
        input[type="text"]:focus, input[type="password"]:focus {
            outline: 2.5px solid #43cea2;
            box-shadow: 0 0 0 3px #fed6e344;
            background: #fff;
        }
        input[type="submit"] {
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
        input[type="submit"]:hover {
            background: linear-gradient(90deg, #185a9d 0%, #43cea2 100%);
            transform: translateY(-2px) scale(1.045);
        }
        @media (max-width: 600px) {
            .login-container { padding: 18px 4vw 18px 4vw; width: 97vw;}
            h2 { font-size: 1.2rem; }
            .login-logo svg { width: 36px; height: 36px;}
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
    <div class="login-container">
        <div class="login-logo">
            <!-- Assistant SVG Icon -->
            <svg viewBox="0 0 64 64" aria-label="Lab Assistant">
                <defs>
                    <radialGradient id="grad1" cx="50%" cy="50%" r="60%">
                        <stop offset="0%" stop-color="#fff" stop-opacity="0.9"/>
                        <stop offset="100%" stop-color="#43cea2" stop-opacity="0.8"/>
                    </radialGradient>
                </defs>
                <circle cx="32" cy="32" r="30" fill="url(#grad1)" />
                <ellipse cx="32" cy="26" rx="12" ry="12" fill="#fed6e3"/>
                <ellipse cx="32" cy="50" rx="17" ry="8" fill="#43cea2" opacity="0.85"/>
                <circle cx="32" cy="26" r="9" fill="#fff"/>
                <ellipse cx="27" cy="25" rx="1.5" ry="2.2" fill="#2a5298"/>
                <ellipse cx="37" cy="25" rx="1.5" ry="2.2" fill="#2a5298"/>
                <rect x="29" y="30" width="6" height="2.5" rx="1.2" fill="#fcb69f"/>
                <!-- Lab flask -->
                <rect x="44" y="38" width="6" height="13" rx="2" fill="#185a9d" opacity="0.7"/>
                <ellipse cx="47" cy="38" rx="3" ry="2" fill="#fff" opacity="0.7"/>
            </svg>
        </div>
        <h2>Lab Assistant Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required autocomplete="username">
            <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
            <input type="submit" value="Login">
             <div class="link">
            <p><a href="assi_forget.php">Forgot Password?</a></p>
        </div>
        </form>
    </div>
</body>
</html>
