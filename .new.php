<?php
include 'db.php';

$username = 'teacher1'; // Change as needed
$password = 'teacher123'; // Change as needed
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO teachers (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed_password);
if ($stmt->execute()) {
    echo "New teacher added successfully.";
} else {
    echo "Error: " . $stmt->error;
}
?>
