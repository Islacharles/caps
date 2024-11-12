<?php
include 'connection.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering
ob_start();

$parent_id = isset($_GET['parent_id']) ? intval($_GET['parent_id']) : 0;

// Validate parent ID
if ($parent_id <= 0) {
    echo "Invalid parent ID.";
    exit;
}

try {
    // Check if parent_id exists in parent_acc
    $sql_check = "SELECT id FROM parent_acc WHERE id = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $parent_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        echo "Parent ID not found.";
        exit;
    }
    $stmt_check->close();

    // Fetch child information
    $sql = "SELECT c.*, a.fullname AS child_teacher
            FROM child_acc c 
            LEFT JOIN admin_staff a ON c.child_teacher = a.id 
            WHERE c.parent_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $parent_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "No child found for this parent.";
        exit;
    }

    $child = $result->fetch_assoc();
    $stmt->close();

    // Prepare image data
    if (!empty($child['child_image'])) {
        $image_data = base64_encode($child['child_image']);
        $image_src = 'data:image/jpeg;base64,' . $image_data; // Change 'jpeg' to the correct format if necessary
    } else {
        $image_src = '/placeholder.svg?height=100&width=100';
    }

    // Prepare HTML response
    $html = "<div>
                <img src='" . htmlspecialchars($image_src) . "' alt='Child Image' style='width:100px;height:100px;'>
                <p><strong>Child Name:</strong> " . htmlspecialchars($child['child_name']) . "</p>
                <p><strong>Student ID:</strong> " . htmlspecialchars($child['id']) . "</p>
                <p><strong>Section:</strong> " . htmlspecialchars($child['child_section']) . "</p>
                <p><strong>Grade:</strong> " . htmlspecialchars($child['child_grade']) . "</p>
                <p><strong>Age:</strong> " . htmlspecialchars($child['child_age']) . "</p>
                <p><strong>Address:</strong> " . htmlspecialchars($child['child_address']) . "</p>
                <p><strong>Adviser:</strong> " . htmlspecialchars($child['child_teacher']) . "</p>
              </div>";
    
    echo $html;

} catch (Exception $e) {
    echo "An error occurred: " . htmlspecialchars($e->getMessage());
} finally {
    $conn->close();
}

// End output buffering and flush
ob_end_flush();
?>
