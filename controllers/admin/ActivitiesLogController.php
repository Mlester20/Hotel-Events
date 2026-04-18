<?php
session_start();
require_once __DIR__ . '/../../db/config/config.php';
require_once __DIR__ . '/../../models/admin/ActiviesLogModel.php';

class ActivitiesLogController {
    private $model;

    public function __construct($con) {
        $this->model = new ActiviesLogModel($con);
    }

    public function index() {
        $limit = 10; // items per page
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        if ($page < 1) $page = 1;

        $offset = ($page - 1) * $limit;

        $logs = $this->model->getLogs($limit, $offset);
        $total = $this->model->countLogs();

        $totalPages = ceil($total / $limit);

        return [
            'logs' => $logs,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ];
    }
}

$controller = new ActivitiesLogController($con);
$data = $controller->index();

$logs = $data['logs'];
$currentPage = $data['currentPage'];
$totalPages = $data['totalPages'];