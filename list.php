<?php
include_once 'db.php';

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin') {
    echo "You do not have permission to view this page.";
    exit();
}

$sql = "SELECT id, first_name, last_name, address, photo from users";
$stmt = $conn->prepare($sql);
$stmt->execute();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">List of Users</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Address</th>
                    <th>Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row['first_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['last_name']) . '</td>';
                        echo '<td>' . htmlspecialchars($row['address']) . '</td>';
                        echo '<td>';
                        if (!empty($row['photo']) && file_exists($row['photo'])) {
                            echo '<img src="' . htmlspecialchars($row['photo']) . '" alt="User Photo" style="width: 100px; height: auto;">';
                        } else {
                            echo 'No photo available';
                        }
                        echo '<td>';
                        echo '<a href="view_data.php?id=' . $row['id'] . '" class="btn btn-info btn-sm">View</a> ';
                        echo '<a href="edit.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Edit</a> ';
                        echo '<a href="delete.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm">Delete</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="5" class="text-center">No users found</td></tr>';
                }
                ?>
            </tbody>

        </table>
        <a href="index.php" class="btn btn-primary">Add New User</a>
    </div>