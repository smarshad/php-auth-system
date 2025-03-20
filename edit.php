<?php
session_start();
require_once __DIR__ . '/classes/Auth.php';

$auth = new Auth();
$error = '';

// Ensure the user is logged in
if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}

// Get logged-in user's data
$userId = $_SESSION['user_id'];
$userData = $auth->getUserById($userId);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username   = trim($_POST['username']);
    $email      = trim($_POST['email']);
    $password   = trim($_POST['password']);
    $profilePic = $userData['profile_pic']; // Keep the old profile pic by default

    try {
        // Handle profile picture upload
        if (isset($_FILES['profilePic']) && $_FILES['profilePic']['error'] === UPLOAD_ERR_OK) {
            $profilePic = $auth->uploadProfilePic($_FILES['profilePic']);
        }

        // Update user data
        $updateResult = $auth->updateProfile($userId, $username, $email, $password, $profilePic);
        
        if ($updateResult === true) {
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['profile_pic'] = $profilePic;
            header("Location: profile.php"); // Redirect after successful update
            exit;
        } else {
            $error = $updateResult; // Display the error message
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
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .mt-30 {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container mt-30">
        <h1 class="text-center">Edit Profile</h1>
        <p id="error-message" style="color: red;"></p>
        <?php if ($error): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($userData['username']); ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="text" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">New Password (leave blank to keep current password)</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="profilePic" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profilePic" name="profilePic">
                <?php if (!empty($userData['profile_pic'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($userData['profile_pic']); ?>" alt="Profile Picture" class="mt-2" style="width:100px;">
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
        <br>
        <p><a href="profile.php">Back to Dashboard</a></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
