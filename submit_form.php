<?php
ob_start();

header('Content-Type: application/json');

include 'connection.php';

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . htmlspecialchars($conn->connect_error)]);
    exit; // Stop execution if connection fails
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate required fields
    $required_fields = ['fullname', 'contact_number', 'email', 'address', 'password', 'confirm_password'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
            exit;
        }
    }

    // Form data processing
    $fullname = trim($_POST['fullname']);
    $contact_number = trim($_POST['contact_number']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Password mismatch check
    if ($password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
        exit;
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Validate and sanitize contact number (only 11 digits)
    if (!preg_match('/^\d{11}$/', $contact_number)) {
        echo json_encode(['success' => false, 'message' => 'Contact number must be exactly 11 digits.']);
        exit;
    }


    // Handle image upload
    $image_data = null;
    if (isset($_FILES['parent_image']) && $_FILES['parent_image']['error'] == UPLOAD_ERR_OK) {
        $parent_image = $_FILES['parent_image']['tmp_name'];
        $image_data = file_get_contents($parent_image);
    } else {
        
        $image_data = null;
    }

    // Insert parent data into the database, including confirm_password
    $parent_sql = "INSERT INTO parent_acc (fullname, contact_number, email, address, parent_image, password, confirm_password) 
                   VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($parent_sql);

    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Database prepare failed: ' . htmlspecialchars($conn->error)]);
        exit;
    }

   
    $stmt->bind_param("sssssss", $fullname, $contact_number, $email, $address, $image_data, $password_hash, $confirm_password);

    if ($stmt->execute()) {
        $parent_id = $stmt->insert_id;
        echo json_encode(['success' => true, 'parent_id' => $parent_id]);  
    } else {
        echo json_encode(['success' => false, 'message' => 'Error inserting parent information: ' . htmlspecialchars($stmt->error)]);
    }

    $stmt->close();
}

$conn->close();


ob_end_flush();
?>
