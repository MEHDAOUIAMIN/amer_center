<?php
$conn = new mysqli("localhost","root","","medical_center");
if($conn->connect_error){
    die("Connection Failed: ".$conn->connect_error);
}

// تحديث كل الأطباء بكلمة مرور واحدة مشفرة
$new_plain_password = "123456"; // الكلمة الجديدة لجميع الأطباء

$result = $conn->query("SELECT * FROM doctors");
while($doctor = $result->fetch_assoc()){
    $id = $doctor['id'];
    $hashed_password = password_hash($new_plain_password, PASSWORD_DEFAULT);
    $conn->query("UPDATE doctors SET password='$hashed_password' WHERE id=$id");
}

echo "Passwords updated successfully! All doctors can now login with password: $new_plain_password";
?>
