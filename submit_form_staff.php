<?php

// Include the database connection file
include 'connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fullname = $_POST['fullname'];
    $contact_number = $_POST['contact_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];  // This is the confirm password field
    $role = $_POST['role'];

    // Check if the password and confirm password match
    if ($password !== $confirm_password) {
        // If passwords don't match, show an alert and redirect back to the form
        echo '<script>alert("Passwords do not match. Please try again."); window.location.href="staff.php";</script>';
        exit();  // Stop further execution
    }

    // Hash the password using password_hash()
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO admin_staff (fullname, contact_number, email, address, password, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $fullname, $contact_number, $email, $address, $hashed_password, $role);

    // Execute the statement
    if ($stmt->execute()) {
        // If successful, show a success message and redirect
        echo '<script>alert("Record successfully added"); window.location.href="staff.php";</script>';
    } else {
        // If there's an error, show an error message and redirect
        echo '<script>alert("Error occurred while adding record."); window.location.href="staff.php";</script>';
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
