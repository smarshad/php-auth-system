<?php
session_start();

require_once __DIR__ . '/classes/Auth.php';
$auth = new Auth();
$error = '';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);


    try {
        if ($auth->login($username, $password)) {
            header("Location: profile.php");
            exit();
        } else {
            $error = "Invalid username or password";
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
</body>
<script type="text/javascript">
    /**
     * Display error mesage on inside given element.
     * @param {string} elementId The ID of the error message container.
     * @param {string} message The error message to display.
     */

    function showError(elementId, message) {
        let errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.innerHTML = message;
            errorElement.style.color = 'red';
        }
    }

    /**
     * Validates a username (3-20 characters, only letters, numbers, and underscores).
     * @param {string} username
     * @returns {boolean|string} - Returns true if valid, otherwise an error message.
     */
    function isValidUsername(username) {
        if (!username.trim()) {
            return "Username cannot be empty.";
        }

        const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
        if (!usernameRegex.test(username)) {
            return "Username must be 3-20 characters long and contain only letters, numbers, and underscores.";
        }

        return true;
    }


    /**
     * Validates an email format.
     * @param {string} email
     * @returns {boolean|string} - Returns true if valid, otherwise an error message.
     */
    function isValidEmail(email) {
        if (!email.trim()) {
            return "Email cannot be empty.";
        }

        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(email)) {
            return "Invalid email format.";
        }

        return true;
    }


    /** 
     * Validates a password (at least 6 characters 1 letter, 1 number)
     * @param {string} password
     * @returns {boolean}
     */

    function isValidPassword(password) {
        if (!password.trim()) {
            return "Password cannot be empty.";
        }

        const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;
        if (!passwordRegex.test(password)) {
            return "Password must be at least 6 characters long and contain at least one letter and one number.";
        }

        return true;
    }


    /** 
     * Validates an upload image file (JPG, JPEG, PNG, max size 500KB)
     * @param {File} file
     * @returns {boolean|string} Returns true if valid, else an error message.
     */

    function isValidFile(file) {

        // Chekc File upload or not
        if (!file) {
            return "File Empty please Upload File";
        }

        const allowedFileTypes = ["image/jpeg", "image/jpg", "image/png"];

        // Check File Types
        if (!allowedFileTypes.includes(file.type)) {
            return "Only JPG, JPEG, PNG Files are allowed";
        }

        // Check File Size
        if (file.size > 500000) {
            return "File Size must be less than 500KB";
        }

        return true;
    }

    /** 
     * Handle Signup form validation
     * $param {Event} event
     */

    function validateSignupForm(event) {
        event.preventDefault();
        var username = document.getElementById('username').value.trim();
        var password = document.getElementById('password').value.trim();
        var errorElement = document.getElementById("error-message");
        errorElement.innerHTML = "";


        let validationResult = isValidUsername(username);
        if (validationResult !== true) {
            showError("error-message", validationResult);
            document.getElementById('username').focus();
            return false;
        }



        let validationPassword = isValidPassword(password);
        if (validationPassword !== true) {
            showError("error-message", validationPassword);
            return false;
        }


        document.getElementById("login-form").submit();

    }
</script>

</html>