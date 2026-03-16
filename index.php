<?php
session_start(); // مهم لتتبع الجلسة
$conn = new mysqli("localhost", "root", "", "medical_center");
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}

// جلب كل الأطباء من قاعدة البيانات
$doctors = $conn->query("SELECT * FROM doctors ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AMER CENTER</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVBAR -->
<section class="navbar">
    <div class="logo">
        <img src="images/hospital.png" alt="Logo">
        <h1>AMER <span>Center Medical</span></h1>
    </div>

<div class="nav-buttons">
    <a href="booking.php"><button class="button">Book Now</button></a>
    <a href="admin/doctor-login.php"><button class="button">Login</button></a>
</div>
</section>

<!-- HERO -->
<section class="hero">
  <div class="hero-text">
    <h1>Welcome to</h1>
    <span>AMER Medical Center</span>
    <div class="tbal3it">
      <h2>✔ Quality Care</h2>
      <h2>✔ Easy Booking</h2>
      <h2>✔ Professional Doctors</h2>
    </div>
  </div>
  <div class="hero-img">
    <img src="images/vecteezy_modern-hospital-building-with-red-cross-symbol-isolated-on_60423723.png" alt="Hospital">
  </div>
</section>

<!-- INFO BAR -->
<section class="info-bar">
   <div class="info-item">
      <div class="icon">📍</div>
      <div>
         <h4>Amar Medical Center</h4>
         <p>Your Health is Our Priority</p>
      </div>
   </div>
   <div class="divider"></div>
   <div class="info-item">
      <div class="icon">🕒</div>
      <div>
         <h4>Open Daily</h4>
         <p>8:00 AM – 8:00 PM</p>
      </div>
   </div>
   <div class="divider"></div>
   <div class="info-item">
      <div class="icon">📞</div>
      <div>
         <h4>Contact Us</h4>
         <p>+213 XXX XXX XXX</p>
      </div>
   </div>
</section>

<!-- DOCTORS -->
<section class="doctors">
  <h2>Our Doctors</h2>
  <div class="doctor-grid">
    <?php while($row = $doctors->fetch_assoc()): ?>
      <div class="card">
        <img src="images/doctor.png" alt="Doctor <?php echo htmlspecialchars($row['name']); ?>">
        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
        <p><?php echo htmlspecialchars($row['speciality']); ?></p>
        <button onclick="bookDoctor('<?php echo htmlspecialchars($row['name']); ?>')">📅 Book</button>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<script>
function bookDoctor(doctorName){
    localStorage.setItem('selectedDoctor', doctorName);
    window.location.href = 'booking.php';
}
</script>

<!-- CONTACT -->
<section class="contact-modern">
  <h1 class="contact-title">Get In Touch</h1>
  <p class="contact-sub">We are always ready to help you</p>
  <div class="contact-modern-container">
    <div class="contact-modern-info">
      <div class="info-box">
        <span>📍</span>
        <div>
          <h3>Location</h3>
          <p>Oran Medical Street</p>
        </div>
      </div>
      <div class="info-box">
        <span>📞</span>
        <div>
          <h3>Phone</h3>
          <p>+213 555 00 00</p>
        </div>
      </div>
      <div class="info-box">
        <span>📧</span>
        <div>
          <h3>Email</h3>
          <p>amercenter@gmail.com</p>
        </div>
      </div>
      <div class="social-icons">
        <a href="#"><img src="images/facebook.png" alt="Facebook"></a>
        <a href="#"><img src="images/instagram.png" alt="Instagram"></a>
        <a href="#"><img src="images/linkedin.png" alt="LinkedIn"></a>
        <a href="#"><img src="images/twitter.png" alt="Twitter"></a>
      </div>
    </div>
    <form class="contact-modern-form">
      <input type="text" placeholder="Your Name" required>
      <input type="email" placeholder="Your Email" required>
      <textarea placeholder="Write your message"></textarea>
      <button type="submit">Send Message</button>
    </form>
  </div>
</section>

</body>
</html>