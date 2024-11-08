<?php
include 'connection.php';


$title = $_POST['title'];
$byline = $_POST['byline'];
$paragraph = $_POST['paragraph'];
$date = $_POST['date'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO events (title, byline, paragraph, date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $title, $byline, $paragraph, $date);

// Execute the statement
if ($stmt->execute()) {
    echo '<script>alert("Event Recorded"); window.location.href="events.php";</script>';
} else {
    echo '<script>alert("Error"); window.location.href="events.php";</script>';
}

// Close statement and connection
$stmt->close();
$conn->close();
?>