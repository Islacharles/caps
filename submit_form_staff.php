<?php
include 'connection.php';


$stmt = $conn->prepare("INSERT INTO admin_staff (fullname, contact_number, email, address, password, role) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $fullname, $contact_number, $email, $address, $password, $role);

// Get form data
$fullname = $_POST['fullname'];
$contact_number = $_POST['contact_number'];
$email = $_POST['email'];
$address = $_POST['address'];
$password = $_POST['password']; 
$role = $_POST['role'];

// Execute the statement
if ($stmt->execute()) {
    echo '<script>alert("Record successfully added"); window.location.href="staff.php";</script>';
} else {
    echo '<script>alert("Error"); window.location.href="staff.php";</script>';
}

// Close connections
$stmt->close();

?>