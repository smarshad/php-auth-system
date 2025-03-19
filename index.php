<?php
session_start();
require_once __DIR__ . '/classes/Auth.php';

$auth  = new Auth();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = trim($_POST['password']);
    $profilepic = null; // Set to null instead of an empty string

    try {

        if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
            $profilepic = $auth->uploadProfilePic($_FILES['profilePic']);
        }
        $signupResult = $auth->signup($username, $email, $password, $profilepic);
        if ($signupResult === true) {
            header("Location: login.php");
            exit;
        } else {
            $error = $signupResult; // Show actual error message
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NEC SWS Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .mt-30 {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container mt-30">
        <h1 class="text-center">Sign UP!</h1>
        <p id="error-message" style="color: red;"></p>
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data" id="signup-form" onsubmit="validateSignupForm(event)">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="text" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="profilePic" class="form-label">Profile Pic</label>
                <input type="file" class="form-control" id="profilePic" name="profilePic">
            </div>
            <button type="submit" class="btn btn-primary">Signup</button>
        </form>
        <br>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/validation.js"></script>
</body>
</html>