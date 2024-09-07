<?php
include 'db.php';

$name = $_POST['name'];
$subject = $_POST['subject'];
$marks = $_POST['marks'];

$stmt = $conn->prepare("SELECT * FROM students WHERE name=? AND subject=?");
$stmt->bind_param("ss", $name, $subject);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if ($student) {
    $newMarks = $student['marks'] + $marks;
    $updateStmt = $conn->prepare("UPDATE students SET marks=? WHERE id=?");
    $updateStmt->bind_param("ii", $newMarks, $student['id']);
    $updateStmt->execute();
} else {
    $insertStmt = $conn->prepare("INSERT INTO students (name, subject, marks) VALUES (?, ?, ?)");
    $insertStmt->bind_param("ssi", $name, $subject, $marks);
    $insertStmt->execute();
}
?>
