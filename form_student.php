<?php
session_start();
include("db.php");

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: student_login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $prno = mysqli_real_escape_string($conn, $_POST['prno']);
    $branch = mysqli_real_escape_string($conn, $_POST['branch']);
    $batch = (int)$_POST['batch'];
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $faculty_name = mysqli_real_escape_string($conn, $_POST['faculty_name']);
    $pc_no = mysqli_real_escape_string($conn, $_POST['pc_no']);

    if (!preg_match("/^[A-Za-z ]+$/", $fullname)) {
        $message = "Error: Full Name must contain only letters and spaces.";
    } elseif (!preg_match("/^\d{14}$/", $prno)) {
        $message = "Error: PRN must be exactly 14 digits.";
    } else {
        $_SESSION['prno'] = $prno;

        $insertQuery = "INSERT INTO student_forms (fullname, prno, branch, batch, subject, faculty_name, pc_no, login_time)
                        VALUES ('$fullname', '$prno', '$branch', $batch, '$subject', '$faculty_name', '$pc_no', NOW())";

        if (mysqli_query($conn, $insertQuery)) {
            $message = "Form submitted successfully!";
        } else {
            $message = "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Information Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;400&family=Roboto:wght@400;300&display=swap" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Roboto', sans-serif;
            overflow: hidden;
            position: relative;
        }
        .background-svg {
            position: absolute;
            z-index: 0;
            top: 0; left: 0; width: 100vw; height: 100vh;
            pointer-events: none;
            opacity: 0.18;
        }
      .college-overlay {
    position: fixed;
    z-index: 1;
    top: 50%;
    left: 50%;
    width: 100vw;
    text-align: center;
    pointer-events: none;
    transform: translate(-50%, -50%);
    font-family: 'Montserrat', sans-serif;
    font-size: 6rem; /* <-- MAKE IT BIG! */
    font-weight: 900;
    color: #1e3c72;
    letter-spacing: 2.5px;
    opacity: 0.13;
    text-shadow: 0 4px 24px #fed6e3, 0 2px 8px #fff;
    user-select: none;
    white-space: pre-line;
    line-height: 1.05;
}
@media (max-width: 900px) {
    .college-overlay { font-size: 3rem; }
}
@media (max-width: 600px) {
    .college-overlay { font-size: 1.3rem; }
}

        .form-container {
            position: relative;
            z-index: 2;
            background: rgba(255,255,255,0.75);
            border-radius: 24px;
            box-shadow: 0 8px 32px 0 rgba(70,70,120,0.13), 0 1.5px 8px rgba(30,60,114,0.09);
            padding: 24px 18px 18px 18px;
            width: 380px;
            max-width: 96vw;
            backdrop-filter: blur(14px) saturate(170%);
            border: 2px solid rgba(255,255,255,0.18);
            text-align: left;
            animation: appear 1.2s cubic-bezier(.5,1.5,.5,1);
            margin: 18px 0;
        }
        @keyframes appear {
            from { opacity: 0; transform: translateY(40px);}
            to { opacity: 1; transform: translateY(0);}
        }
        .logo-anim {
            width: 48px;
            margin: 0 auto 10px auto;
            display: block;
            animation: spin 4s linear infinite;
            filter: drop-shadow(0 2px 10px #a8edea88);
        }
        @keyframes spin {
            0% {transform: rotate(0deg);}
            100% {transform: rotate(360deg);}
        }
        h2 {
            font-family: 'Montserrat', sans-serif;
            color: #1e3c72;
            font-size: 1.25rem;
            margin-bottom: 4px;
            font-weight: 700;
            letter-spacing: 1px;
            text-align: center;
            text-shadow: 0 2px 6px #fed6e366;
        }
        .subtitle {
            color: #2a5298;
            font-size: 0.98rem;
            margin-bottom: 12px;
            opacity: 0.88;
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            text-align: center;
        }
        .message {
            text-align: center;
            margin-bottom: 8px;
            font-weight: bold;
            padding: 7px;
            border-radius: 7px;
            font-size: 0.98rem;
        }
        .message.success { color: #32cd32; background: #eaffea; border: 1.2px solid #32cd32; }
        .message.error { color: #ff4e50; background: #fff0f0; border: 1.2px solid #ff4e50; }

        label {
            display: block;
            margin-top: 7px;
            font-weight: 600;
            color: #1e3c72;
            font-size: 0.97rem;
            letter-spacing: 0.2px;
        }
        input, select {
            width: 100%;
            padding: 7px 9px;
            margin-top: 3px;
            border: 1.5px solid #e0e0e0;
            border-radius: 9px;
            font-size: 0.97rem;
            background: rgba(255,255,255,0.85);
            transition: all 0.3s cubic-bezier(.5,1.5,.5,1);
            font-family: 'Roboto', sans-serif;
        }
        input:focus, select:focus {
            border-color: #43cea2;
            box-shadow: 0 0 5px #43cea288;
            outline: none;
        }
        button {
            margin-top: 18px;
            width: 100%;
            padding: 11px 0;
            font-size: 1rem;
            font-weight: 600;
            background: linear-gradient(90deg, #43cea2 0%, #185a9d 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 18px rgba(30,60,114,0.10);
            position: relative;
            overflow: hidden;
            transition: all 0.23s cubic-bezier(.5,1.5,.5,1);
        }
        button:after {
            content: "";
            position: absolute;
            left: 50%;
            top: 50%;
            width: 0;
            height: 0;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            transform: translate(-50%,-50%);
            transition: width 0.4s, height 0.4s;
            z-index: 0;
        }
        button:hover:after {
            width: 240%;
            height: 240%;
        }
        button:focus {
            outline: 2px solid #185a9d;
            outline-offset: 2px;
        }
        button span {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
        }
        @media (max-width: 600px) {
            .form-container { padding: 10px 2vw 10px 2vw; }
            .logo-anim { width: 34px; }
            h2 { font-size: 1rem; }
            .college-overlay { font-size: 1.1rem; }
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
    <div class="college-overlay">
        Government College of Engineering,<br>Kolhapur
    </div>
    <div class="form-container">
        <!-- Animated SVG logo -->
        <svg class="logo-anim" viewBox="0 0 64 64" role="img" aria-label="Lab Access Logo">
            <defs>
                <radialGradient id="grad1" cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
                    <stop offset="0%" style="stop-color:#43cea2;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#185a9d;stop-opacity:1" />
                </radialGradient>
            </defs>
            <rect x="8" y="16" width="48" height="32" rx="8" fill="url(#grad1)" />
            <rect x="20" y="28" width="24" height="8" rx="2" fill="#fff" opacity="0.7"/>
            <circle cx="32" cy="32" r="6" fill="#fff" opacity="0.9"/>
            <rect x="12" y="44" width="40" height="4" rx="2" fill="#fcb69f"/>
        </svg>
        <h2>Student Information Form</h2>
        <div class="subtitle">Fill in your details to access the lab.<br>All fields are required.</div>
        <?php
            if (!empty($message)) {
                $class = (strpos($message, 'successfully') !== false) ? 'success' : 'error';
                echo "<div class='message $class'>$message</div>";
            }
        ?>
        <form method="POST" action="">
            <label for="fullname">Full Name</label>
            <input type="text" name="fullname" id="fullname" required pattern="[A-Za-z ]+" title="Only letters and spaces allowed">

            <label for="prno">PRN (14 digits)</label>
            <input type="text" name="prno" id="prno" required pattern="\d{14}" title="Enter exactly 14 digits" maxlength="14">

            <label for="branch">Branch</label>
            <select name="branch" required>
                <option value="">--Select Branch--</option>
                <option value="Computer">Computer</option>
                <option value="Mechanical">Mechanical</option>
                <option value="Electrical">Electrical</option>
                <option value="AIDS">AIDS</option>
                <option value="ENTC">ENTC</option>
            </select>

            <label for="batch">Batch</label>
            <select name="batch" required>
                <option value="">--Select Batch--</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>

            <label for="subject">Subject</label>
            <input type="text" name="subject" required>

            <label for="faculty_name">Faculty Name</label>
            <input type="text" name="faculty_name" required>

            <label for="pc_no">PC No</label>
            <input type="text" name="pc_no" required>

            <button type="submit"><span>🚀 Submit</span></button>
        </form>
    </div>
</body>
</html>
