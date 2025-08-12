<?php
include_once 'db.php';
$id = $_GET['id'];
$db = new Db();
$deleted = $db->delete_by_id('users', $id);
if ($deleted) {
    header("Location: list.php");
    exit();
} else {
    echo "Error: Could not delete user";
}
?>
