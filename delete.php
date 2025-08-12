<?php 
include_once 'db.php';
$id = $_GET['id'];
$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id]);
if ($stmt->rowCount() > 0) {
    header("Location: list.php");
    exit();
} else {
    echo "Error: Could not delete user";
}
