<?php
session_start();

$conn = new mysqli("localhost","root","","medical_center");
if($conn->connect_error){
    die("Connection Failed: ".$conn->connect_error);
}

// احصائيات
$total_doctors = $conn->query("SELECT COUNT(*) as total FROM doctors")->fetch_assoc()['total'];
$total_appointments = $conn->query("SELECT COUNT(*) as total FROM appointments")->fetch_assoc()['total'];

$today = date('Y-m-d');

$today_appointments = $conn->query("SELECT COUNT(*) as total 
FROM appointments 
WHERE appointment_date='$today'")->fetch_assoc()['total'];

$pending_appointments = $conn->query("SELECT COUNT(*) as total 
FROM appointments 
WHERE status='pending'")->fetch_assoc()['total'];

$completed_appointments = $conn->query("SELECT COUNT(*) as total 
FROM appointments 
WHERE status='done'")->fetch_assoc()['total'];

$cancelled_appointments = $conn->query("SELECT COUNT(*) as total 
FROM appointments 
WHERE status='cancelled'")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="style2.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{background:#f4f6f9;font-family:Arial}

.layout{display:flex}

.sidebar{
width:220px;
background:#2c3e50;
min-height:100vh;
padding:20px;
}

.sidebar h2{color:#fff;margin-bottom:30px}

.sidebar a{
display:block;
color:#ecf0f1;
padding:10px;
margin-bottom:10px;
text-decoration:none;
border-radius:6px;
}

.sidebar a:hover,
.sidebar .active{
background:#27ae60;
}

.main{
flex:1;
padding:30px;
}

.topbar{
display:flex;
justify-content:space-between;
align-items:center;
}

.logout{
background:#e74c3c;
color:white;
border:none;
padding:10px 18px;
border-radius:6px;
cursor:pointer;
}

.cards{
display:flex;
flex-wrap:wrap;
gap:20px;
margin-top:30px;
}

.card{
background:white;
padding:20px;
border-radius:12px;
flex:1;
min-width:200px;
box-shadow:0 2px 10px rgba(0,0,0,0.1);
text-align:center;
}

.card h3{color:#555}
.card p{
font-size:28px;
font-weight:bold;
color:#27ae60;
margin-top:10px;
}

.charts{
display:flex;
gap:20px;
margin-top:40px;
flex-wrap:wrap;
}

.chart-card{
background:white;
padding:20px;
border-radius:12px;
flex:1;
min-width:300px;
box-shadow:0 2px 10px rgba(0,0,0,0.1);
}
</style>
</head>
<body>

<div class="layout">

<aside class="sidebar">
<h2>Admin Panel</h2>
<a class="active" href="dashboard.php">Dashboard</a>
<a href="appointments.php">Appointments</a>
<a href="doctors.php">Doctors</a>
<a href="add-doctor.php">Add Doctor</a>
</aside>

<main class="main">

<div class="topbar">
<h1>Dashboard</h1>
<a href="../logout.php"><button class="logout">Logout</button></a>
</div>

<div class="cards">
<div class="card">
<h3>Total Doctors</h3>
<p><?php echo $total_doctors; ?></p>
</div>

<div class="card">
<h3>Total Appointments</h3>
<p><?php echo $total_appointments; ?></p>
</div>

<div class="card">
<h3>Today</h3>
<p><?php echo $today_appointments; ?></p>
</div>

<div class="card">
<h3>Pending</h3>
<p><?php echo $pending_appointments; ?></p>
</div>

<div class="card">
<h3>Done</h3>
<p><?php echo $completed_appointments; ?></p>
</div>

<div class="card">
<h3>Cancelled</h3>
<p><?php echo $cancelled_appointments; ?></p>
</div>
</div>

<div class="charts">

<div class="chart-card">
<h3>Appointments Status</h3>
<canvas id="statusChart"></canvas>
</div>

<div class="chart-card">
<h3>Today Appointments</h3>
<canvas id="dailyChart"></canvas>
</div>

</div>

</main>
</div>

<script>
const ctx1 = document.getElementById('statusChart');

new Chart(ctx1, {
type: 'doughnut',
data: {
labels: ['Pending','Done','Cancelled'],
datasets: [{
data: [<?php echo $pending_appointments ?>,
<?php echo $completed_appointments ?>,
<?php echo $cancelled_appointments ?>],
backgroundColor:['orange','green','red']
}]
}
});

const ctx2 = document.getElementById('dailyChart');

new Chart(ctx2, {
type: 'bar',
data: {
labels:['Today'],
datasets:[{
label:'Appointments',
data:[<?php echo $today_appointments ?>],
backgroundColor:'blue'
}]
}
});
</script>

</body>
</html>