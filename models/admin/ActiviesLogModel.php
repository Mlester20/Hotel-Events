<?php
require_once __DIR__ . '/../BaseModel.php';

class ActiviesLogModel extends BaseModel {
    protected $table = 'activities_log';

    public function getLogs($limit, $offset) {
        try {
            $query = "
                SELECT 
                    al.id,
                    al.user_id,
                    u.full_name AS user_name,
                    u.role AS roles,
                    al.action,
                    al.description,
                    al.created_at
                FROM {$this->table} al
                LEFT JOIN users u ON u.user_id = al.user_id
                ORDER BY al.created_at ASC
                LIMIT ? OFFSET ?
            ";

            $stmt = $this->con->prepare($query);
            $stmt->bind_param("ii", $limit, $offset);
            $stmt->execute();

            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);

        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage(), 500);
        }
    }

    // total rows (for pagination)
    public function countLogs() {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->con->query($query);
        return $result->fetch_assoc()['total'];
    }
}