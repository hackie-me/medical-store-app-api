<?php
require '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = $fun->verify_token();
    echo json_encode(["status" => true, "data" => $data]);
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}
