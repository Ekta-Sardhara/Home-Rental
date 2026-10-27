<?php
session_start();

$conn = new mysqli("localhost", "root", "", "home_rental_system");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    // Use correct column names
    $sql = "INSERT INTO messages (sender_name, sender_email, message_text) 
            VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                alert('Your message has been sent to admin successfully!');
                window.location.href='index.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Error sending message: ".$conn->error."');
                window.location.href='index.php';
              </script>";
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>