<?php
include 'db.php'; // Ensure this includes your database connection script

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $marks = $_POST['marks'];

    // Prepare and bind parameters to update query
    $stmt = $conn->prepare("UPDATE students SET name=?, subject=?, marks=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $subject, $marks, $id);

    if ($stmt->execute()) {
        // Successfully updated
        echo "Student updated successfully";
        http_response_code(200);
    } else {
        // Failed to update
        echo "Failed to update student";
        http_response_code(500);
    }

    $stmt->close();
} else {
    // Handle invalid request method
    http_response_code(405); // Method Not Allowed
}
?>
