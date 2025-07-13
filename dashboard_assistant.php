<?php
include("db.php");
session_start();

if (!isset($_SESSION['assistant_logged_in'])) {
    header("Location: assistant_login.php");
    exit();
}

date_default_timezone_set('Asia/Kolkata');

$whereClause = "WHERE 1=1";

// Date filter
if (!empty($_GET['search_date'])) {
    $search_date = mysqli_real_escape_string($conn, $_GET['search_date']);
    $whereClause .= " AND (DATE(login_time) = '$search_date' OR DATE(logout_time) = '$search_date')";
}

// Status filter
$filter = $_GET['filter'] ?? 'all';
if ($filter === 'active') {
    $whereClause .= " AND logout_time IS NULL";
}

$query = "SELECT * FROM student_forms $whereClause ORDER BY login_time DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Assistant Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;400&family=Roboto:wght@400;300&display=swap" rel="stylesheet">
    <meta http-equiv="refresh" content="15">
    <style>
        body {
            margin: 0;
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            font-family: 'Roboto', sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 48px auto;
            padding: 32px;
            background: rgba(255,255,255,0.95);
            border-radius: 24px;
            box-shadow: 0 12px 48px rgba(0,0,0,0.1);
        }
        .logout-btn {
            text-align: right;
            margin-bottom: 16px;
        }
        .logout-btn a {
            padding: 10px 20px;
            background: linear-gradient(90deg, #ff4d4d, #fcb69f);
            color: white;
            text-decoration: none;
            border-radius: 24px;
            font-weight: bold;
            font-family: 'Montserrat', sans-serif;
        }
        h2 {
            text-align: center;
            color: #1e3c72;
            font-family: 'Montserrat', sans-serif;
        }
        .filters {
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="date"], select {
            padding: 10px;
            font-size: 1rem;
            border-radius: 8px;
            margin: 0 10px;
        }
        button {
            padding: 10px 20px;
            font-size: 1rem;
            background: #43cea2;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 22px rgba(0,0,0,0.06);
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background: linear-gradient(90deg, #43cea2, #185a9d);
            color: white;
        }
        tr:nth-child(even) { background: #f9f9f9; }
        tr:nth-child(odd) { background: #f1faff; }
        .status-active { color: green; font-weight: bold; }
        .status-inactive { color: red; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }

        /* Hide buttons and unnecessary items when printing */
        @media print {
            .logout-btn, .filters, button {
                display: none;
            }
            body {
                background: white;
            }
            .container {
                box-shadow: none;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logout-btn">
            <a href="index.html">Logout</a>
        </div>
        <h2>Student Lab Records</h2>
        <div class="filters">
            <form method="GET" action="">
                <label for="search_date">Date:</label>
                <input type="date" name="search_date" value="<?php echo htmlspecialchars($_GET['search_date'] ?? ''); ?>">
                
                <label for="filter">Show:</label>
                <select name="filter">
                    <option value="all" <?php if ($filter === 'all') echo 'selected'; ?>>All</option>
                    <option value="active" <?php if ($filter === 'active') echo 'selected'; ?>>Active Only</option>
                </select>

                <button type="submit">Filter</button>
            </form>

            <!-- Print Button -->
            <!--<button onclick="window.print()">🖨️ Print Records</button> -->
        </div>
        <div class="table-container">
            <table>
                <tr>
                    <th>Sr. No</th>
                    <th>Full Name</th>
                    <th>PRN No</th>
                    <th>Branch</th>
                    <th>Batch</th>
                    <th>Subject</th>
                    <th>Faculty Name</th>
                    <th>PC No</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>Status</th>
                </tr>
                <?php
                $srNo = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    $login_time = $row['login_time'];
                    $logout_time = $row['logout_time'];

                    $formattedLogin = !empty($login_time)
                        ? date("d-m-Y h:i A", strtotime($login_time))
                        : '-';

                    if (is_null($logout_time)) {
                        $formattedLogout = '<span class="status-pending">Still Logged In</span>';
                        $status = 'Active';
                        $statusClass = 'status-active';
                    } else {
                        $formattedLogout = date("d-m-Y h:i A", strtotime($logout_time));
                        $status = 'Inactive';
                        $statusClass = 'status-inactive';
                    }
                ?>
                    <tr>
                        <td><?php echo $srNo++; ?></td>
                        <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($row['prno']); ?></td>
                        <td><?php echo htmlspecialchars($row['branch']); ?></td>
                        <td><?php echo htmlspecialchars($row['batch']); ?></td>
                        <td><?php echo htmlspecialchars($row['subject']); ?></td>
                        <td><?php echo htmlspecialchars($row['faculty_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['pc_no'] ?? 'N/A'); ?></td>
                        <td><?php echo $formattedLogin; ?></td>
                        <td><?php echo $formattedLogout; ?></td>
                        <td class="<?php echo $statusClass; ?>"><?php echo $status; ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
         <button onclick="window.print()">🖨️ Print Records</button>
    </div>
</body>
</html>
