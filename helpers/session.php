<?php
class SessionManager {

    // Start session safely
    public static function start(): void {
        if(session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Set session values
    public static function set(array $data): void {
        foreach ($data as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    // Get session value safely
    public static function get(string $key) {
        return $_SESSION[$key] ?? null;
    }

    // Destroy session
    public static function destroy(): void {
        session_unset();
        session_destroy();
    }

    // Check if user is logged in
    public static function isLoggedIn(): bool {
        return !empty(self::get('user_id')) && !empty(self::get('role'));
    }

    // Redirect logged-in user to proper dashboard
    public static function redirectLoggedIn(): void {
        if(!self::isLoggedIn()) return;

        $role = self::get('role');

        switch($role) {
            case 'admin':
                header("Location: resources/views/admin/dashboard.php");
                exit();
            case 'front_desk':
                header("Location: resources/views/frontdesk/dashboard.php");
                exit();
            case 'user':
                header("Location: resources/views/pages/dashboard.php");
                exit();
            default:
                self::destroy();
                header("Location: login.php");
                exit();
        }
    }
}
?>