<?php

require_once __DIR__ . '/../config/DbConnection.php';

class Auth
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = DbConnection::getInstance();
        $this->conn = $this->db->getConnection();
    }

    public function signup($username, $email, $password, $profilePic)
    {
        try {
            $this->validateInput($username, $password, $email);
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $this->conn->prepare(
                "INSERT INTO users (username, email, password, profile_pic) VALUES (:username, :email, :password, :profilePic)"
            );
            
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':profilePic' => $profilePic
            ]);
            
            return true;
        } catch (Exception $exception) {
            $this->handleException($exception, "Error on Signup");
            return false;
        }
    }

    public function login($username, $password)
    {
        try {
            $this->validateUsername($username);
            $this->validatePassword($password);
            
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $this->setUserSession($user);
                return true;
            }
            return false;
        } catch (Exception $exception) {
            $this->handleException($exception, "Error on Login");
            return false;
        }
    }

    public function uploadProfilePic($file)
    {
        try {
            $this->validateFile($file);
            
            $targetDir = __DIR__ . '/../uploads/';
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            
            $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $newFilename = uniqid("profile_", true) . "." . $imageFileType;
            $finalPath = $targetDir . $newFilename;

            if (!move_uploaded_file($file['tmp_name'], $finalPath)) {
                throw new Exception("Error saving uploaded file.");
            }

            return $newFilename;
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    public function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    private function validateInput($username, $password, $email)
    {
        $this->validateUsername($username);
        $this->validateEmail($email);
        $this->validatePassword($password);
    }

    private function validateUsername($username)
    {
        if (empty($username) || !preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
            throw new Exception("Invalid username: must be 3-20 characters long and contain only letters, numbers, and underscores.");
        }
    }

    private function validateEmail($email)
    {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email address.");
        }
    }

    private function validatePassword($password)
    {
        if (empty($password) || strlen($password) < 6) {
            throw new Exception("Password must be at least 6 characters long.");
        }
    }

    private function validateFile($file)
    {
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            throw new Exception("No file uploaded or invalid file.");
        }

        $check = getimagesize($file['tmp_name']);
        if (!$check) {
            throw new Exception("File is not a valid image.");
        }

        $mimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($check['mime'], $mimeTypes)) {
            throw new Exception("Invalid file format. Only JPG, PNG, GIF allowed.");
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception("File is too large. Max 5MB allowed.");
        }

        return;
    }

    private function setUserSession($user)
    {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['profile_pic'] = $user['profile_pic'];
    }

    private function handleException($exception, $message)
    {
        $this->db->errorLog($exception);
        die("$message, please check error log.");
    }
}
