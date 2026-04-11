<?php

    class Redirect{
        private static array $roleRoutes = [
            'admin' => '/../../resources/views/admin/settings.php',
            'front_desk' => '/../../resources/views/frontdesk/settings.php',
            'user' => '/../../resources/views/pages/settings.php',
        ];

        //get redirect URL based on user role
        public static function byRole(string $role): string {
            return self::$roleRoutes[$role] ?? '/../../index.php';
        }

        //perform the redirect
        public static function go(string $role): void{
            header("Location: " . self::byRole($role));
            // Ensure no further code is executed after redirect
            exit();
        }
    }
?>