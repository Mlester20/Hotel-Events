<?php
require_once __DIR__ . '/../models/BaseModel.php';

class ActivityLogger extends BaseModel {

    public function log($user_id, $role, $action, $module, $reference_id = null, $reference_table = null, $description = '', $status = 'success') {
        try {
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

            $stmt = $this->con->prepare("CALL log_activity(?, ?, ?, ?, ?, ?, ?, ?, ?)");

            if (!$stmt) {
                throw new Exception($this->con->error);
            }

            $stmt->bind_param(
                "issssisss",
                $user_id,
                $role,
                $action,
                $module,
                $reference_id,
                $reference_table,
                $description,
                $ip,
                $status
            );

            $stmt->execute();
            $stmt->close();

            // FIX: prevent "commands out of sync"
            while ($this->con->more_results() && $this->con->next_result()) {;}

        } catch (Exception $e) {
            
        }
    }
}