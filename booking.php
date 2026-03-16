<?php
$conn = new mysqli("localhost", "root", "", "medical_center");
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

$doctors = $conn->query("SELECT * FROM doctors ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Appointment</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<section class="booking-section" id="book">
  <div class="booking-container">
    <h1>Book Your Appointment</h1>

    <form class="booking-form" action="submit-booking.php" method="POST">

      <input type="text" name="fullname" placeholder="Full Name" required>

      <input type="email" name="email" placeholder="Email Address" required>

      <input type="tel" name="phone" placeholder="Phone Number" required>

      <!-- ⭐ هنا التعديل المهم -->
      <select id="doctorSelect" name="doctor_id" required>
        <option value="">Select Doctor</option>
        <?php while($row = $doctors->fetch_assoc()): ?>
          <option value="<?php echo $row['id']; ?>">
            <?php echo $row['name']; ?> - <?php echo $row['speciality']; ?>
          </option>
        <?php endwhile; ?>
      </select>

      <div class="datetime">
        <!-- ⭐ هنا التعديل -->
        <input type="date" name="appointment_date" required>
        <input type="time" name="appointment_time" required>
      </div>

      <button type="submit">Confirm Booking</button>

    </form>
  </div>
</section>

<script>
window.addEventListener('DOMContentLoaded', () => {
    const selectedDoctor = localStorage.getItem('selectedDoctor');
    if(selectedDoctor){
        document.getElementById('doctorSelect').value = selectedDoctor;
        localStorage.removeItem('selectedDoctor');
    }
});
</script>

</body>
</html>