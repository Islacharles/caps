<?php
// Include database connection file
include 'connection.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve user input
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Prepare SQL statement to check if the email exists
    $stmt = $conn->prepare("SELECT id, password, fullname, role FROM admin_staff WHERE email = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    // Check if the email exists
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $storedPassword, $name, $role);
        $stmt->fetch();
        
        // Verify password (plain-text comparison)
        if ($password === $storedPassword) {
            // Password is correct, start session and store user info
            session_start();
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_role'] = $role;
            
            // Redirect to student.php
            header("Location: student.php");
            exit();
        } else {
            // Invalid password
            echo '<script>alert("Invalid password"); window.location.href="login.php";</script>';
        }
    } else {
        // Invalid email
        echo '<script>alert("Invalid email"); window.location.href="login.php";</script>';
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
