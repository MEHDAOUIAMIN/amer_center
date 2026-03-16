<?php
session_start();
if(!isset($_SESSION['doctor_id'])){
    header("Location: doctor-login.php");
    exit;
}

$conn = new mysqli("localhost","root","","medical_center");
if($conn->connect_error){
    die("Connection Failed: ".$conn->connect_error);
}

$doctor_id = $_SESSION['doctor_id'];
$today = date('Y-m-d');

// تحديث الحالة Accept / Cancel
if(isset($_GET['accept'])){
    $id = $_GET['accept'];
    $conn->query("UPDATE appointments SET status='done' WHERE id=$id AND doctor_id=$doctor_id");
    header("Location: doctor-appointments.php");
    exit;
}
if(isset($_GET['cancel'])){
    $id = $_GET['cancel'];
    $conn->query("UPDATE appointments SET status='cancelled' WHERE id=$id AND doctor_id=$doctor_id");
    header("Location: doctor-appointments.php");
    exit;
}

// إحصائيات سريعة
$total_appointments = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE doctor_id=$doctor_id")->fetch_assoc()['total'];
$today_appointments = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE doctor_id=$doctor_id AND appointment_date='$today'")->fetch_assoc()['total'];
$pending_appointments = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE doctor_id=$doctor_id AND status='pending'")->fetch_assoc()['total'];
$completed_appointments = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE doctor_id=$doctor_id AND status='done'")->fetch_assoc()['total'];
$cancelled_appointments = $conn->query("SELECT COUNT(*) as total FROM appointments WHERE doctor_id=$doctor_id AND status='cancelled'")->fetch_assoc()['total'];

// جلب كل المواعيد الخاصة بالطبيب
$result = $conn->query("
    SELECT id, fullname, phone, email, appointment_date, appointment_time, status
    FROM appointments
    WHERE doctor_id=$doctor_id
    ORDER BY appointment_date ASC, appointment_time ASC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Doctor Appointments</title>
<link rel="stylesheet" href="style2.css">
<style>
body {
    margin:0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background:#f4f6f8;
}

/* Sidebar ثابت */
.sidebar {
    position: fixed;
    left:0; top:0;
    width:220px; height:100%;
    background:#2c3e50;
    color:#fff;
    display:flex;
    flex-direction:column;
    padding:20px 0;
}
.sidebar h2 {
    text-align:center;
    margin-bottom:30px;
    font-size:22px;
}
.sidebar a {
    display:block;
    color:#fff;
    padding:15px 25px;
    margin:5px 0;
    text-decoration:none;
    transition:0.2s;
    font-weight:500;
}
.sidebar a.active, .sidebar a:hover {
    background:#34495e;
    border-radius:5px;
}

/* المحتوى الرئيسي */
.main {
    margin-left:220px; 
    padding:25px;
}

/* Topbar */
.topbar {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}
.topbar h1 {
    color:#333;
}
.topbar p {
    color:#777;
}

/* Cards الإحصائيات */
.cards {
    display:flex;
    gap:15px;
    margin-bottom:25px;
}
.card {
    flex:1;
    background:#fff;
    padding:20px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    transition:0.3s;
}
.card:hover {
    transform: translateY(-5px);
}
.card h3 { margin-bottom:12px; color:#555; }
.card p { font-size:22px; font-weight:bold; color:#27ae60; }

/* جدول المواعيد */
.table-box {
    overflow-x:auto;
    background:#fff;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}
table {
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}
th, td {
    padding:14px;
    border-bottom:1px solid #e0e0e0;
    text-align:center;
    font-size:14px;
}
th {
    background:#f7f7f7;
}
tr:hover { background:#f1f1f1; }
.status.pending { color:orange; font-weight:bold; }
.status.done { color:green; font-weight:bold; }
.status.cancelled { color:red; font-weight:bold; }
a.action-btn {
    text-decoration:none;
    padding:6px 12px;
    border-radius:6px;
    background:#007bff;
    color:#fff;
    margin:0 3px;
    font-size:13px;
    transition:0.2s;
}
a.action-btn:hover { opacity:0.85; }
</style>
</head>
<body>

<div class="sidebar">
    <h2>Dr. <?php echo $_SESSION['doctor_name']; ?></h2>
    <a href="doctor-dashboard.php">Dashboard</a>
    <a class="active" href="doctor-appointments.php">Appointments</a>
    <a href="doctor-logout.php">Logout</a>
</div>

<div class="main">
    <div class="topbar">
        <h1>Appointments</h1>
        <p><?php echo date('l, d M Y'); ?></p>
    </div>

    <!-- Cards الإحصائيات -->
    <div class="cards">
        <div class="card"><h3>Total</h3><p><?php echo $total_appointments; ?></p></div>
        <div class="card"><h3>Today</h3><p><?php echo $today_appointments; ?></p></div>
        <div class="card"><h3>Pending</h3><p><?php echo $pending_appointments; ?></p></div>
        <div class="card"><h3>Completed</h3><p><?php echo $completed_appointments; ?></p></div>
        <div class="card"><h3>Cancelled</h3><p><?php echo $cancelled_appointments; ?></p></div>
    </div>

    <!-- جدول المواعيد -->
    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    echo "<tr>
                        <td>{$row['fullname']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['phone']}</td>
                        <td>{$row['appointment_date']}</td>
                        <td>{$row['appointment_time']}</td>
                        <td class='status {$row['status']}'>{$row['status']}</td>
                        <td>
                            <a class='action-btn' href='doctor-appointments.php?accept={$row['id']}'>Accept</a>
                            <a class='action-btn' href='doctor-appointments.php?cancel={$row['id']}'>Cancel</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No appointments found.</td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>