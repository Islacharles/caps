<?php
include 'connection.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . htmlspecialchars($conn->connect_error)]));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input fields
    $parent_id = trim($_POST['parent_id']);
    $child_name = trim($_POST['child_name']);
    $student_id = trim($_POST['student_id']);
    $child_teacher = trim($_POST['child_teacher']);
    $child_age = trim($_POST['child_age']);
    $child_grade = trim($_POST['child_grade']);
    $child_section = trim($_POST['child_section']);
    $child_address = trim($_POST['child_address']);
    $authorized_person = trim($_POST['authorized_person']);  // Get the authorized person ID

    // Basic validation
    if (empty($parent_id) || !is_numeric($parent_id)) {
        echo json_encode(['success' => false, 'message' => 'Invalid Parent ID']);
        exit;  // Stop script execution after returning response
    }
    if (empty($child_name)) {
        echo json_encode(['success' => false, 'message' => 'Child Name is required']);
        exit;
    }
    if (empty($student_id)) {
        echo json_encode(['success' => false, 'message' => 'Student ID is required']);
        exit;
    }
    if (empty($authorized_person) || !is_numeric($authorized_person)) {
        echo json_encode(['success' => false, 'message' => 'Invalid Authorized Person ID']);
        exit;
    }

    // Handle image upload
    if (isset($_FILES['child_image']) && $_FILES['child_image']['error'] == UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['child_image']['tmp_name'];
        $image_data = file_get_contents($image_tmp_name);  // Read image content into a variable
    } else {
        echo json_encode(['success' => false, 'message' => 'Error in image upload or no image uploaded']);
        exit;
    }

    // Prepare SQL query to insert data into the database
    $child_sql = "INSERT INTO child_acc 
                  (parent_id, child_name, student_id, child_teacher, child_age, child_grade, child_section, child_address, child_image, authorized_id) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($child_sql);

    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Database prepare failed: ' . htmlspecialchars($conn->error)]);
        exit;
    }

    
    $stmt->bind_param("ississssss", $parent_id, $child_name, $student_id, $child_teacher, $child_age, $child_grade, $child_section, $child_address, $image_data, $authorized_person);

    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Child record created successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error inserting child record: ' . htmlspecialchars($stmt->error)]);
    }

    $stmt->close();
}

$conn->close();
?>
