<?php
$conn = new mysqli("localhost","root","","medical_center");
if($conn->connect_error){
    die("Connection Failed: ".$conn->connect_error);
}

$message = "";

// نفذ فقط إذا تم إرسال النموذج
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $spec = isset($_POST['speciality']) ? $_POST['speciality'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $days = isset($_POST['days']) ? $_POST['days'] : '';

    // prepared statement للأمان
    $stmt = $conn->prepare("INSERT INTO doctors (name, speciality, phone, working_days) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $spec, $phone, $days);

    if($stmt->execute()){
        $message = "Doctor Added Successfully";
    } else {
        $message = "Error adding doctor";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Doctor</title>
<style>
/* ---- CSS Page ---- */
body{
    font-family: Arial, sans-serif;
    background-color: #f4f4f7;
    margin: 0;
}

.layout{
    display: flex;
}

/* Sidebar */
.sidebar{
    width: 220px;
    background-color: #2c3e50;
    height: 100vh;
    color: #fff;
    padding: 20px;
    box-sizing: border-box;
}

.sidebar h2{
    margin-top: 0;
    font-size: 24px;
    margin-bottom: 20px;
}

.sidebar a{
    display: block;
    color: #fff;
    text-decoration: none;
    padding: 10px 0;
    margin-bottom: 5px;
    border-radius: 4px;
}

.sidebar a.active,
.sidebar a:hover{
    background-color: #34495e;
}

/* Main */
.main{
    flex: 1;
    padding: 30px;
    box-sizing: border-box;
}

.topbar h1{
    margin: 0 0 20px 0;
}

.form-box{
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    max-width: 500px;
}

.form-box label{
    display: block;
    margin-top: 15px;
    margin-bottom: 5px;
    font-weight: bold;
}

.form-box input{
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.form-box button{
    margin-top: 20px;
    padding: 12px;
    width: 100%;
    background-color: #2c3e50;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.form-box button:hover{
    background-color: #34495e;
}

.success{
    color: green;
    margin-top: 15px;
    font-weight: bold;
    text-align: center;
}
</style>
</head>

<body>

<div class="layout">

   <!-- SIDEBAR -->
   <aside class="sidebar">
      <h2>Admin</h2>
      <a href="dashboard.php">Dashboard</a>
      <a href="appointments.php">Appointments</a>
      <a href="doctors.php">Doctors</a>
      <a class="active" href="add-doctor.php">Add Doctor</a>
   </aside>

   <!-- MAIN -->
   <main class="main">

      <div class="topbar">
         <h1>Add Doctor</h1>
      </div>

      <!-- FORM -->
      <div class="form-box">
         <?php if($message) echo "<p class='success'>$message</p>"; ?>

         <form action="add-doctor.php" method="POST">

            <label>Doctor Name</label>
            <input type="text" name="name" placeholder="Enter doctor name" required>

            <label>Speciality</label>
            <input type="text" name="speciality" placeholder="Cardiologist">

            <label>Phone</label>
            <input type="text" name="phone" placeholder="0550000000">

            <label>Working Days</label>
            <input type="text" name="days" placeholder="Sat - Wed">

            <button type="submit">Save Doctor</button>
         </form>
      </div>

   </main>

</div>

</body>
</html>