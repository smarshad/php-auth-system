<?php
session_start();

require_once __DIR__ . '/classes/Auth.php';
$auth = new Auth();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    try {
        $loginResult = $auth->login($username, $password);
        if ($loginResult === true) {
            header("Location: profile.php");
            exit;
        } else {
            $error = $loginResult; // Show actual error message
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
        <h1 class="text-center">Login!</h1>
        <p id="error-message" style="color: red;"></p>
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data" id="login-form" onsubmit="validateLoginForm(event)">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <br>
        <p>Dont'nt have an account? <a href="index.php">Signup</a></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/validation.js"></script>
</body>
</html>