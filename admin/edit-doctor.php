<?php
$conn = new mysqli("localhost","root","","medical_center");
if($conn->connect_error){
    die("Connection Failed: ".$conn->connect_error);
}

// الحصول على ID الطبيب من الرابط
$id = $_GET['id'] ?? 0;

// تحديث البيانات عند الضغط على زر Save
if(isset($_POST['save'])){
    $name = $_POST['name'];
    $spec = $_POST['speciality'];
    $phone = $_POST['phone'];
    $days = $_POST['days'];

    $conn->query("UPDATE doctors SET 
        name='$name',
        speciality='$spec',
        phone='$phone',
        working_days='$days'
        WHERE id=$id");

    header("Location: doctors.php"); // إعادة التوجيه بعد التحديث
}

// جلب بيانات الطبيب الحالي
$result = $conn->query("SELECT * FROM doctors WHERE id=$id");
$doctor = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Doctor</title>
<link rel="stylesheet" href="style2.css">
</head>
<body>

<div class="layout">

   <aside class="sidebar">
      <h2>Admin</h2>
      <a href="dashboard.php">Dashboard</a>
      <a href="appointments.php">Appointments</a>
      <a class="active" href="doctors.php">Doctors</a>
      <a href="add-doctor.php">Add Doctor</a>
   </aside>

   <main class="main">

      <div class="topbar">
         <h1>Edit Doctor</h1>
         <button class="logout">Logout</button>
      </div>

      <div class="form-box">

         <form method="POST">

            <label>Doctor Name</label>
            <input type="text" name="name" value="<?php echo $doctor['name']; ?>" required>

            <label>Speciality</label>
            <input type="text" name="speciality" value="<?php echo $doctor['speciality']; ?>">

            <label>Phone</label>
            <input type="text" name="phone" value="<?php echo $doctor['phone']; ?>">

            <label>Working Days</label>
            <input type="text" name="days" value="<?php echo $doctor['working_days']; ?>">

            <button type="submit" name="save" class="save">Save Changes</button>

         </form>

      </div>

   </main>

</div>

</body>
</html>