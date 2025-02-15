<?php
include './connection.php';

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $conn->real_escape_string($_POST['id']);

    $sql = "DELETE FROM complaints WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "Complaint deleted successfully";
    } else {
        echo "Error deleting complaint: " . $conn->error;
    }
}

$conn->close();
header("Location: admin_dashboard.php");
?>
