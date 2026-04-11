<?php
session_start();
require_once __DIR__ . '/../db/config/config.php';
require_once __DIR__ . '/../models/UpdateProfileModel.php';
require_once __DIR__ . '/../helpers/message.php';
require_once __DIR__ . '/../helpers/redirect.php';

$updateProfileModel = new UpdateProfileModel($con);

// Always fetch fresh user data for display
try {
    $user = $updateProfileModel->getUser($_SESSION['user_id']);
} catch (Exception $e) {
    $user = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['updateProfile'])) {
    $user_id  = (int) $_SESSION['user_id'];
    $full_name       = trim($_POST['full_name']);
    $email           = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password    = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    try {
        // Current password is always required
        if (empty($current_password)) {
            throw new Exception("Current password is required to save changes.");
        }

        if (!password_verify($current_password, $user['password'])) {
            throw new Exception("Current password is incorrect.");
        }

        // Update basic info
        $updateProfileModel->updateProfile([
            'full_name' => $full_name,
            'email'     => $email
        ], $user_id);

        // Handle profile picture upload
        if (!empty($_FILES['profile']['name'])) {
            $allowed  = ['jpg', 'jpeg', 'png', 'gif'];
            $ext      = strtolower(pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION));
            $max_size = 2 * 1024 * 1024; // 2MB

            if (!in_array($ext, $allowed)) {
                throw new Exception("Invalid file type. Only JPG, PNG, and GIF are allowed.");
            }

            if ($_FILES['profile']['size'] > $max_size) {
                throw new Exception("File size exceeds 2MB limit.");
            }

            $upload_dir = __DIR__ . '/../storage/profiles/';

            // Create folder if it doesn't exist
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Delete old photo if it exists
            if (!empty($user['profile'])) {
                $old_file = $upload_dir . $user['profile'];
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }

            $filename    = uniqid('pfp_', true) . '.' . $ext;
            $destination = $upload_dir . $filename;

            if (!move_uploaded_file($_FILES['profile']['tmp_name'], $destination)) {
                throw new Exception("Failed to upload profile picture.");
            }

            $updateProfileModel->updateProfilePicture($filename, $user_id);
            $_SESSION['profile'] = $filename;
        }

        // Handle password change (optional)
        if (!empty($new_password)) {
            if (strlen($new_password) < 8) {
                throw new Exception("New password must be at least 8 characters.");
            }
            if ($new_password !== $confirm_password) {
                throw new Exception("New passwords do not match.");
            }
            $updateProfileModel->updatePassword($new_password, $user_id);
        }

        // Sync session
        $_SESSION['full_name'] = $full_name;
        $_SESSION['email']     = $email;

        setFlash("success" ,"Profile updated successfully.");
        Redirect::go($_SESSION['role']);
        exit();

    } catch (Exception $e) {
        setFlash("danger", $e->getMessage());
        Redirect::go($_SESSION['role']);
        exit();
    }
}
?>