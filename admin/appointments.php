<?php
$conn = new mysqli("localhost","root","","medical_center");
if($conn->connect_error){
    die("Connection Failed: ".$conn->connect_error);
}

// تحديث حالة الموعد عند الضغط Accept أو Cancel
if(isset($_GET['accept'])){
    $id = $_GET['accept'];
    $conn->query("UPDATE appointments SET status='done' WHERE id=$id");
    header("Location: appointments.php");
    exit;
}

if(isset($_GET['cancel'])){
    $id = $_GET['cancel'];
    $conn->query("UPDATE appointments SET status='cancelled' WHERE id=$id");
    header("Location: appointments.php");
    exit;
}

// جلب بيانات المواعيد مع اسم الطبيب
$result = $conn->query("
    SELECT a.id, a.fullname, a.phone, d.name AS doctor_name, 
           a.appointment_date, a.appointment_time, a.status
    FROM appointments a
    JOIN doctors d ON a.doctor_id = d.id
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Appointments</title>
<link rel="stylesheet" href="style2.css">
</head>
<body>

<div class="layout">

   <!-- SIDEBAR -->
   <aside class="sidebar">
      <h2>Admin</h2>
      <a href="dashboard.php">Dashboard</a>
      <a class="active" href="appointments.php">Appointments</a>
      <a href="doctors.php">Doctors</a>
      <a href="add-doctor.php">Add Doctor</a>
   </aside>

   <!-- MAIN -->
   <main class="main">

      <div class="topbar">
         <h1>Appointments</h1>
         <button class="logout">Logout</button>
      </div>

      <!-- TABLE -->
      <div class="table-box">
         <table>
            <thead>
               <tr>
                  <th>Patient</th>
                  <th>Phone</th>
                  <th>Doctor</th>
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
                            <td>{$row['phone']}</td>
                            <td>{$row['doctor_name']}</td>
                            <td>{$row['appointment_date']}</td>
                            <td>{$row['appointment_time']}</td>
                            <td class='{$row['status']}'>{$row['status']}</td>
                            <td>
                                <a href='appointments.php?accept={$row['id']}'>Accept</a>
                                <a href='appointments.php?cancel={$row['id']}'>Cancel</a>
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

   </main>

</div>

</body>
</html>