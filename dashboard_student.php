<?php
include("db.php");
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: student_login.php");
    exit();
}

$username = $_SESSION['username'];
$query = "SELECT * FROM students WHERE username = '$username' LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    echo "Student data not found!";
    exit();
}

$student = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
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
            opacity: 0.13;
        }
        .dashboard {
            position: relative;
            z-index: 1;
            background: rgba(255,255,255,0.88);
            border-radius: 36px;
            box-shadow: 0 12px 48px 0 rgba(70,70,120,0.18), 0 1.5px 8px rgba(30,60,114,0.10), 0 0 44px 0 #43cea288;
            padding: 54px 42px 42px 42px;
            width: 440px;
            max-width: 96vw;
            backdrop-filter: blur(22px) saturate(190%);
            border: 3.5px solid transparent;
            text-align: center;
            animation: appear 1.2s cubic-bezier(.5,1.5,.5,1);
            margin: 40px 0;
            background-clip: padding-box, border-box;
            background-origin: padding-box, border-box;
            overflow: visible;
        }
        .dashboard::before {
            content: '';
            position: absolute;
            inset: -4px;
            z-index: -1;
            border-radius: 40px;
            background: linear-gradient(120deg, #43cea2, #fed6e3, #fcb69f, #185a9d 90%);
            background-size: 200% 200%;
            filter: blur(2px);
            animation: borderMove 6s linear infinite;
        }
        @keyframes borderMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes appear {
            from { opacity: 0; transform: translateY(60px) scale(0.96);}
            to { opacity: 1; transform: translateY(0) scale(1);}
        }
        .avatar-outer {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 16px;
        }
        .avatar-svg {
            width: 92px;
            height: 92px;
            border-radius: 50%;
            background: linear-gradient(135deg, #43cea2 0%, #fed6e3 100%);
            box-shadow: 0 8px 32px #fed6e388, 0 2px 12px #43cea2aa;
            border: 5px solid #fff;
            padding: 7px;
            animation: pulse 2.5s infinite alternate;
            transition: box-shadow 0.3s;
        }
        .avatar-svg:hover {
            box-shadow: 0 0 60px #43cea299, 0 2px 18px #fed6e3cc;
        }
        @keyframes pulse {
            0% { box-shadow: 0 8px 32px #fed6e388, 0 2px 12px #43cea2aa;}
            100% { box-shadow: 0 16px 48px #43cea299, 0 2px 18px #fed6e3cc;}
        }
        h2 {
            font-family: 'Montserrat', sans-serif;
            font-size: 2.2rem;
            color: #1e3c72;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: 1px;
            text-shadow: 0 2px 6px #fed6e366;
            animation: fadeInUp 1s 0.2s both;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(18px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .welcome {
            color: #2a5298;
            font-size: 1.11rem;
            margin-bottom: 28px;
            opacity: 0.93;
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            animation: fadeInUp 1s 0.4s both;
        }
        .info {
            margin-top: 16px;
            text-align: left;
            background: rgba(255,255,255,0.65);
            border-radius: 20px;
            padding: 22px 20px 14px 20px;
            box-shadow: 0 2px 14px #a8edea22;
            font-size: 1.13rem;
            animation: fadeInUp 1s 0.6s both;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 17px;
            font-family: 'Roboto', sans-serif;
            align-items: center;
            transition: background 0.18s;
            border-radius: 10px;
            padding: 3px 0 3px 0;
        }
        .info-row:hover {
            background: rgba(168,237,234,0.13);
        }
        .info-label {
            color: #185a9d;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .info-value {
            color: #2a5298;
            font-weight: 500;
            letter-spacing: 0.1px;
            text-align: right;
        }
        .logout-btn {
            display: inline-block;
            margin: 38px auto 0 auto;
            padding: 13px 36px;
            background: linear-gradient(90deg, #43cea2 0%, #185a9d 100%);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            border: none;
            border-radius: 16px;
            font-size: 1.09rem;
            box-shadow: 0 2px 12px #43cea244;
            transition: background 0.23s, transform 0.15s, box-shadow 0.2s;
            cursor: pointer;
            letter-spacing: 0.5px;
            animation: fadeInUp 1s 0.9s both;
        }
        .logout-btn:hover, .logout-btn:focus {
            background: linear-gradient(90deg, #185a9d 0%, #43cea2 100%);
            transform: translateY(-2px) scale(1.045);
            box-shadow: 0 8px 32px #43cea299;
            outline: none;
        }
        .footer {
            margin-top: 32px;
            color: #2a5298bb;
            font-size: 0.98rem;
            opacity: 0.8;
            font-family: 'Montserrat', sans-serif;
            letter-spacing: 0.7px;
            animation: fadeInUp 1s 1.1s both;
        }
        @media (max-width: 600px) {
            .dashboard { padding: 18px 4vw 18px 4vw; width: 98vw;}
            h2 { font-size: 1.25rem; }
            .avatar-svg { width: 58px; height: 58px; }
            .info { font-size: 0.98rem; }
            .logout-btn { padding: 11px 14px; font-size: 1rem; }
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
    <div class="dashboard">
        <div class="avatar-outer">
            <!-- Animated SVG avatar for a modern look -->
            <svg class="avatar-svg" viewBox="0 0 64 64" aria-label="Student Avatar">
                <defs>
                    <radialGradient id="avatarGrad" cx="50%" cy="50%" r="60%">
                        <stop offset="0%" stop-color="#fff" stop-opacity="0.9"/>
                        <stop offset="100%" stop-color="#43cea2" stop-opacity="0.8"/>
                    </radialGradient>
                </defs>
                <circle cx="32" cy="32" r="30" fill="url(#avatarGrad)" />
                <ellipse cx="32" cy="28" rx="12" ry="12" fill="#fed6e3"/>
                <ellipse cx="32" cy="51" rx="17" ry="8" fill="#43cea2" opacity="0.85"/>
                <circle cx="32" cy="28" r="9" fill="#fff"/>
                <ellipse cx="27" cy="27" rx="1.5" ry="2.2" fill="#2a5298"/>
                <ellipse cx="37" cy="27" rx="1.5" ry="2.2" fill="#2a5298"/>
                <rect x="29" y="32" width="6" height="2.5" rx="1.2" fill="#fcb69f"/>
            </svg>
        </div>
        <h2>
            Welcome, <?php echo htmlspecialchars($student['name'] ?? 'Student'); ?>!
        </h2>
        <div class="welcome">Here are your lab access details:</div>
        <div class="info">
            <div class="info-row">
                <span class="info-label">Username:</span>
                <span class="info-value"><?php echo htmlspecialchars($student['username'] ?? ''); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">PRN No:</span>
                <span class="info-value"><?php echo htmlspecialchars($student['rollno'] ?? ''); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">PC No:</span>
                <span class="info-value"><?php echo htmlspecialchars($student['pc_no'] ?? ''); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Branch:</span>
                <span class="info-value"><?php echo htmlspecialchars($student['branch'] ?? ''); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Batch:</span>
                <span class="info-value"><?php echo htmlspecialchars($student['batch'] ?? ''); ?></span>
            </div>
            <!-- Add more fields as needed -->
        </div>
        <form action="logout.php" method="post">
            <button class="logout-btn" type="submit"><a href="index.html">Logout</button>
        </form>
        <div class="footer">© 2025 College Lab System &mdash; Enjoy your session!</div>
    </div>
</body>
</html>
