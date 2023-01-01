<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $data = null;
    // verifying user token
    if (!empty($fun)) {
        $data = $fun->verify_token();
    }else{
        http_response_code(500);
    }
    echo json_encode($data);
} else {
    http_response_code(405);
}
