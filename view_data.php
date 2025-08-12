<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">View Users Data</h1>

        <?php
        include_once 'db.php';
        $id = $_GET['id'];

       $sql = "SELECT * FROM users WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo '<div class="table-responsive">';
            echo '<table class="table table-striped table-hover">';
            echo '<thead class="table-dark">';
            echo '<tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Address</th>
                        <th>Country</th>
                        <th>Gender</th>
                        <th>Skills</th>
                        <th>Username</th>
                        <th>Department</th>
                        <th>Photo</th>
                        <th>Date</th>
                      </tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['first_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['last_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['address']) . '</td>';
                echo '<td>' . htmlspecialchars($row['country']) . '</td>';
                echo '<td>' . htmlspecialchars($row['gender']) . '</td>';
                echo '<td>' . htmlspecialchars($row['skills']) . '</td>';
                echo '<td>' . htmlspecialchars($row['username']) . '</td>';
                echo '<td>' . htmlspecialchars($row['department']) . '</td>';
                echo '<td>';
                if (!empty($row['photo']) && file_exists($row['photo'])) {
                    echo '<img src="' . htmlspecialchars($row['photo']) . '" alt="User Photo" style="width: 100px; height: auto;">';
                } else {
                    echo 'No photo available';
                }
                echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo '<div class="alert alert-warning">No data saved yet.</div>';
        }


        ?>

        <a href="index.php" class="btn btn-primary">Back to Home</a>
    </div>
</body>

</html>