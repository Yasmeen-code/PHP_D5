<?php
include_once 'db.php';

$id = $_GET['id'] ?? null;
$db = new Db();

$row = $db->get_data_by_id('users', $id);

$skills = !empty($row['skills']) ? explode(',', $row['skills']) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'first_name' => $_POST['first_name'],
        'last_name'  => $_POST['last_name'],
        'address'    => $_POST['address'],
        'country'    => $_POST['country'],
        'gender'     => $_POST['gender'],
        'skills'     => isset($_POST['skills']) ? implode(',', $_POST['skills']) : '',
        'username'   => $_POST['username'],
        'password'   => !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $row['password'],
        'department' => $_POST['department'],
        'photo'       => !empty($_FILES['photo']['name']) ? 'img/' . basename($_FILES['photo']['name']) : $row['photo'],
        'sh68sa'     => $_POST['sh68sa']
    ];
    if (!empty($_FILES['photo']['name'])) {
        move_uploaded_file($_FILES['photo']['tmp_name'], $data['photo']);
    }

    if ($db->update_user($id, $data)) {
        header("Location: list.php");
        exit();
    } else {
        echo "Error: Could not update user";
    }
}
?>


<html>

<head>
    <title>Registration Form - Open Source</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

</head>

<body>
    <div class="container mt-5">
        <h1>Edit User</h1>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-control" name="first_name" value="<?php echo htmlspecialchars($row['first_name']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-control" name="last_name" value="<?php echo htmlspecialchars($row['last_name']); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea class="form-control" name="address" rows="2" required><?php echo htmlspecialchars($row['address']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Country</label>
                <select class="form-select" name="country" required>
                    <option value="mansoura" <?php if ($row['country'] == "mansoura") echo "selected"; ?>>Mansoura</option>
                    <option value="mahalla" <?php if ($row['country'] == "mahalla") echo "selected"; ?>>Mahalla</option>
                    <option value="cairo" <?php if ($row['country'] == "cairo") echo "selected"; ?>>Cairo</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Gender</label><br>
                <input type="radio" name="gender" value="male" <?php if ($row['gender'] == "male") echo "checked"; ?>> Male
                <input type="radio" name="gender" value="female" <?php if ($row['gender'] == "female") echo "checked"; ?>> Female
            </div>

            <div class="mb-3">
                <label class="form-label">Skills</label><br>
                <input type="checkbox" name="skills[]" value="php" <?php if (in_array('php', $skills)) echo "checked"; ?>> PHP
                <input type="checkbox" name="skills[]" value="js" <?php if (in_array('js', $skills)) echo "checked"; ?>> JavaScript
                <input type="checkbox" name="skills[]" value="mysql" <?php if (in_array('mysql', $skills)) echo "checked"; ?>> MySQL
                <input type="checkbox" name="skills[]" value="postgresql" <?php if (in_array('postgresql', $skills)) echo "checked"; ?>> PostgreSQL

            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($row['username']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password </label>
                <input type="password" class="form-control" name="password">
            </div>

            <div class="mb-3">
                <label class="form-label">Department</label>
                <select class="form-select" name="department">
                    <option value="cs" <?php if ($row['department'] == "cs") echo "selected"; ?>>Computer Science</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Photo</label>
                <input type="file" class="form-control" name="photo">
                <?php if (!empty($row['photo']) && file_exists($row['photo'])) : ?>
                    <img src="<?php echo htmlspecialchars($row['photo']); ?>" alt="User Photo" style="width: 100px; height: auto;">
                <?php else : ?>
                    <p>No photo available</p>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Sh68Sa</label>
                <input type="text" class="form-control" name="sh68sa" value="<?php echo htmlspecialchars($row['sh68sa']); ?>" required>
            </div>

            <div class="d-flex justify-content-end">
                <button type="reset" class="btn btn-secondary me-2">Reset</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>

    </div>
</body>

</html>