<?php
require_once __DIR__ . '/../models/BaseModel.php';

class UpdateProfileModel extends BaseModel {
    protected $users = 'users';

    public function getUser($user_id) {
        try {
            $query = "SELECT * FROM {$this->users} WHERE user_id = ?";
            $stmt = $this->con->prepare($query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            throw new Exception("Error fetching user: " . $e->getMessage());
        }
    }

    public function updateProfile($data, $user_id) {
        try {
            $query = "UPDATE {$this->users} SET full_name = ?, email = ?, updated_at = NOW() WHERE user_id = ?";
            $stmt = $this->con->prepare($query);
            $stmt->bind_param("ssi", $data['full_name'], $data['email'], $user_id);
            if ($stmt->execute()) {
                $stmt->close();
                return true;
            }
            throw new Exception("Failed to execute query");
        } catch (Exception $e) {
            throw new Exception("Error updating profile: " . $e->getMessage());
        }
    }

    public function updatePassword($password, $user_id) {
        try {
            $hashed = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
            $query = "UPDATE {$this->users} SET password = ?, updated_at = NOW() WHERE user_id = ?";
            $stmt = $this->con->prepare($query);
            $stmt->bind_param("si", $hashed, $user_id);
            if ($stmt->execute()) {
                $stmt->close();
                return true;
            }
            throw new Exception("Failed to execute query");
        } catch (Exception $e) {
            throw new Exception("Error updating password: " . $e->getMessage());
        }
    }

    public function updateProfilePicture($filename, $user_id) {
        try {
            $query = "UPDATE {$this->users} SET profile = ?, updated_at = NOW() WHERE user_id = ?";
            $stmt = $this->con->prepare($query);
            $stmt->bind_param("si", $filename, $user_id);
            if ($stmt->execute()) {
                $stmt->close();
                return true;
            }
            throw new Exception("Failed to execute query");
        } catch (Exception $e) {
            throw new Exception("Error updating profile picture: " . $e->getMessage());
        }
    }
}
?>