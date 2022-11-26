<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = null;
    // verifying auth token
    if (!empty($fun)) {
        $data = $fun->verify_token();
    }else{
        http_response_code(500);
    }
    echo json_encode(["status" => true, "data" => $data]);
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
    http_response_code(405);
}
