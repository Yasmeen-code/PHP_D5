<?php
function validateInput($data)
{
    return htmlspecialchars(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $first_name = validateInput($_POST['first_name'] ?? '');
    $last_name = validateInput($_POST['last_name'] ?? '');
    $address = validateInput($_POST['address'] ?? '');
    $country = validateInput($_POST['country'] ?? '');
    $gender = validateInput($_POST['gender'] ?? '');
    $skills = $_POST['skills'] ?? [];
    $email = validateInput($_POST['username'] ?? '');
    $raw_password = $_POST['password'] ?? '';
    $department = validateInput($_POST['department'] ?? '');
    $photo = $_FILES['photo'] ?? null;
    $photo_name = $photo['name'];
    $photo_tmp = $photo['tmp_name'];
    $photo_destination = 'img/' . basename($photo_name);
    if (!move_uploaded_file($photo_tmp, $photo_destination)) {
        echo "Error uploading photo.";
        exit();
    }
    $sh68sa = validateInput($_POST['sh68sa'] ?? '');

    if (is_array($skills)) {
        $skills = implode(',', array_map('validateInput', $skills));
    }

    if (
        empty($first_name) || empty($last_name) || empty($address) || empty($country) ||
        empty($gender) || empty($skills) || empty($email) || empty($raw_password) ||
        empty($department) || empty($sh68sa) || empty($_FILES['photo']['name'])
    ) {
        echo "All fields are required.";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    if (strlen($raw_password) < 6) {
        echo "Password must be at least 6 characters.";
        exit();
    }

    $password_hashed = password_hash($raw_password, PASSWORD_DEFAULT);

    include 'db.php';


    $sql = "INSERT INTO users 
            (first_name, last_name, address, country, gender, skills, username, password, department, sh68sa , photo) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

    $stmt = $conn->prepare($sql);
    $saved = $stmt->execute([
        $first_name,
        $last_name,
        $address,
        $country,
        $gender,
        $skills,
        $email,
        $password_hashed,
        $department,
        $sh68sa,
        $photo_destination
    ]);

    if ($saved) {
        header("Location: list.php");
        exit();
    } else {
        echo "Error: Could not save data.";
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
        <h1 class="mb-4">Registration Form</h1>
    <form action="" method="POST" enctype="multipart/form-data">

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="firstName" class="form-label">First Name</label>
                <input type="text" class="form-control" name="first_name" required>
            </div>
            <div class="col-md-6">
                <label for="lastName" class="form-label">Last Name</label>
                <input type="text" class="form-control" name="last_name" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" name="address" rows="2" required></textarea>
        </div>

        <div class="mb-3">
            <label for="country" class="form-label">Country</label>
            <select class="form-select" name="country" required>
                <option value="">Select City</option>
                <option value="mansoura">Mansoura</option>
                <option value="mahalla">Mahalla</option>
                <option value="cairo">Cairo</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Gender</label>
            <div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                    <label class="form-check-label" for="male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" id="female" value="female" required>
                    <label class="form-check-label" for="female">Female</label>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Skills</label>
            <div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="skills[]" value="php" id="php">
                    <label class="form-check-label" for="php">PHP</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="skills[]" value="js" id="js">
                    <label class="form-check-label" for="js">JavaScript</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="skills[]" value="mysql" id="mysql">
                    <label class="form-check-label" for="mysql">MySQL</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="skills[]" value="postgresql" id="postgresql">
                    <label class="form-check-label" for="postgresql">PostgreSQL</label>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <select class="form-select" id="department" name="department" required>
                <option value="cs">Computer Science </option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Photo</label>
            <input type="file" class="form-control" id="photo" name="photo" required>
        </div>
        <div class="mb-3">
            <label for="sh68sa" class="form-label">Sh68Sa</label>
            <input type="text" class="form-control" id="sh68sa" name="sh68sa" placeholder="Enter " required>
        </div>


        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="reset" class="btn btn-secondary">Reset</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>

        
    </form>
    </div>
    

</body>

</html>