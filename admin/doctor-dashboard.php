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

$total = $conn->query("SELECT COUNT(*) t FROM appointments WHERE doctor_id=$doctor_id")->fetch_assoc()['t'];
$today_count = $conn->query("SELECT COUNT(*) t FROM appointments WHERE doctor_id=$doctor_id AND appointment_date='$today'")->fetch_assoc()['t'];
$pending = $conn->query("SELECT COUNT(*) t FROM appointments WHERE doctor_id=$doctor_id AND status='pending'")->fetch_assoc()['t'];
$done = $conn->query("SELECT COUNT(*) t FROM appointments WHERE doctor_id=$doctor_id AND status='done'")->fetch_assoc()['t'];

$last = $conn->query("
SELECT fullname, appointment_date, appointment_time, status
FROM appointments
WHERE doctor_id=$doctor_id
ORDER BY appointment_date DESC
LIMIT 5
");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Doctor Dashboard</title>

<style>
body{
    margin:0;
    font-family:Segoe UI;
    background:#f4f7fb;
}

/* SIDEBAR */
.sidebar{
    position:fixed;
    left:0;
    top:0;
    width:240px;
    height:100%;
    background:linear-gradient(180deg,#0f4c81,#1c7ed6);
    color:white;
    padding-top:30px;
}

.sidebar h2{
    text-align:center;
    margin-bottom:40px;
}

.sidebar a{
    display:block;
    color:white;
    padding:15px 30px;
    text-decoration:none;
    transition:.3s;
}

.sidebar a:hover{
    background:rgba(255,255,255,0.15);
}

.active{
    background:rgba(255,255,255,0.25);
}

/* MAIN */
.main{
    margin-left:240px;
    padding:30px;
}

/* TOPBAR */
.topbar{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.date{
    background:white;
    padding:8px 15px;
    border-radius:8px;
    box-shadow:0 2px 6px rgba(0,0,0,.1);
}

/* CARDS */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
    transition:.3s;
}

.card:hover{
    transform:translateY(-5px);
}

.card h3{
    color:#888;
    margin-bottom:10px;
}

.card p{
    font-size:26px;
    font-weight:bold;
    color:#0f4c81;
}

/* TABLE */
.table{
    background:white;
    border-radius:12px;
    padding:20px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #eee;
}

.pending{color:orange;font-weight:bold;}
.done{color:green;font-weight:bold;}
.cancelled{color:red;font-weight:bold;}
</style>

</head>
<body>

<div class="sidebar">
    <h2>Dr. <?php echo $_SESSION['doctor_name']; ?></h2>
    <a class="active" href="#">Dashboard</a>
    <a href="doctor-appointments.php">Appointments</a>
    <a href="doctor-logout.php">Logout</a>
</div>

<div class="main">

<div class="topbar">
    <h1>Doctor Dashboard</h1>
    <div class="date"><?php echo date('l d M Y'); ?></div>
</div>

<div class="cards">
    <div class="card">
        <h3>Total Appointments</h3>
        <p><?php echo $total; ?></p>
    </div>

    <div class="card">
        <h3>Today</h3>
        <p><?php echo $today_count; ?></p>
    </div>

    <div class="card">
        <h3>Pending</h3>
        <p><?php echo $pending; ?></p>
    </div>

    <div class="card">
        <h3>Completed</h3>
        <p><?php echo $done; ?></p>
    </div>
</div>

<div class="table">
    <h2>Last Appointments</h2>
    <table>
        <tr>
            <th>Patient</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
        </tr>

        <?php while($r = $last->fetch_assoc()): ?>
        <tr>
            <td><?php echo $r['fullname']; ?></td>
            <td><?php echo $r['appointment_date']; ?></td>
            <td><?php echo $r['appointment_time']; ?></td>
            <td class="<?php echo $r['status']; ?>"><?php echo $r['status']; ?></td>
        </tr>
        <?php endwhile; ?>

    </table>
</div>

</div>
</body>
</html>