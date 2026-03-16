<?php
session_start();
$conn = new mysqli("localhost","root","","medical_center");
if($conn->connect_error){
    die("Connection Failed: ".$conn->connect_error);
}

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ⭐ تحقق من doctor
    $result = $conn->query("SELECT * FROM doctors WHERE email='$email'");
    if($result->num_rows == 1){
        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password'])){

            // ⭐ Session خاصة بالطبيب
            $_SESSION['doctor_id'] = $user['id'];
            $_SESSION['doctor_name'] = $user['name'];

            header("Location: doctor-dashboard.php");
            exit;

        }else{
            $error = "Incorrect password";
        }

    }else{

        // ⭐ تحقق من admin
        $result = $conn->query("SELECT * FROM admins WHERE email='$email'");
        if($result->num_rows == 1){

            $user = $result->fetch_assoc();

            if(password_verify($password, $user['password'])){

                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_name'] = $user['name'];

                header("Location: dashboard.php");
                exit;

            }else{
                $error = "Incorrect password";
            }

        }else{
            $error = "Email not found";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="style2.css">
</head>
<body>

<div class="login-box">
<h2>Login</h2>

<form method="POST">
<input type="email" name="email" placeholder="Email" required>
<input type="password" name="password" placeholder="Password" required>
<button name="login">Login</button>
</form>

<?php
if(isset($error)){
    echo "<p style='color:red;'>$error</p>";
}
?>

</div>

</body>
</html>