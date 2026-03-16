<?php
// اتصال بقاعدة البيانات
$conn = new mysqli("localhost","root","","medical_center");
if($conn->connect_error){
    die("Connection Failed: ".$conn->connect_error);
}

// حذف طبيب إذا تم الضغط على Delete
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM doctors WHERE id=$id");
    header("Location: doctors.php"); // إعادة تحميل الصفحة بعد الحذف
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Doctors Management</title>
<link rel="stylesheet" href="style2.css">
</head>
<body>

<div class="layout">

   <!-- SIDEBAR -->
   <aside class="sidebar">
      <h2>Admin</h2>

      <a href="dashboard.php">Dashboard</a>
      <a href="appointments.php">Appointments</a>
      <a class="active" href="doctors.php">Doctors</a>
      <a href="add-doctor.php">Add Doctor</a>
   </aside>

   <!-- MAIN -->
   <main class="main">

      <div class="topbar">
         <h1>Doctors</h1>
         <button class="logout">Logout</button>
      </div>

      <!-- DOCTORS LIST -->
      <div class="doctor-grid">

      <?php
      $result = $conn->query("SELECT * FROM doctors ORDER BY id DESC");
      if($result->num_rows > 0){
          while($row = $result->fetch_assoc()){
              echo '
              <div class="doctor-card">
                 <h3>'.$row['name'].'</h3>
                 <p>'.$row['speciality'].'</p>
                 <p>Phone: '.$row['phone'].'</p>
                 <p>Days: '.$row['working_days'].'</p>
                 <a href="edit-doctor.php?id='.$row['id'].'" class="edit">Edit</a>
                 <a href="doctors.php?delete='.$row['id'].'" class="delete" onclick="return confirm(\'Are you sure?\')">Delete</a>
              </div>
              ';
          }
      } else {
          echo "<p>No doctors found.</p>";
      }
      ?>

      </div>

   </main>

</div>

</body>
</html>