<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM teachers WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Debugging statements
    if (!$user) {
        echo "No user found with username: $username";
    } else {
        echo "User found: " . print_r($user, true);
        echo "Provided password: $password";
        echo "Stored hashed password: " . $user['password'];
    }

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        header("Location: home.php");
        exit();
    } else {
        echo "Invalid username or password";
    }
}
?>
