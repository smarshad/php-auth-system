<?php
session_start();
require_once __DIR__ . '/classes/Auth.php';

$auth = new Auth();

if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit();
}

// Get user details from session
$username = $_SESSION['username'];
$profile_pic = !empty($_SESSION['profile_pic']) ? "../uploads/" . $_SESSION['profile_pic'] : "https://via.placeholder.com/150";
$email = !empty($_SESSION['email']) ?  $_SESSION['email'] : "";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-card {
            max-width: 400px;
            margin: auto;
            margin-top: 50px;
            text-align: center;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007bff;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card profile-card shadow p-4">
            <img src="<?php echo htmlspecialchars($profile_pic); ?>" alt="Profile Picture" class="profile-img">
            <h3 class="mt-3"><?php echo htmlspecialchars($username); ?></h3>
            <h3 class="mt-3"><?php echo htmlspecialchars($email); ?></h3>
            <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
            <a href="edit.php" class="btn btn-danger mt-3">Edit</a>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>