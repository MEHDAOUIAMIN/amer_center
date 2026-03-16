<?php

$conn = new mysqli("localhost","root","","medical_center");
if($conn->connect_error){
    die("Connection Failed: ".$conn->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];

    $status = "pending";

    $stmt = $conn->prepare("
        INSERT INTO appointments
        (fullname, phone, email, doctor_id, appointment_date, appointment_time, status)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sssisss",
        $fullname,
        $phone,
        $email,
        $doctor_id,
        $date,
        $time,
        $status
    );

    if($stmt->execute()){
        echo "<script>
            alert('Appointment booked successfully');
            window.location.href='booking.php';
        </script>";
    }else{
        echo "Error: ".$stmt->error;
    }

}
?>