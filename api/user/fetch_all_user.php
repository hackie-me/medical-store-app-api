<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require '../../config/config.php';
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!empty($fun)) {
        $fun->verify_token();
    }else{
        http_response_code(500);
    }
    // fetching all user list
    $data = null;
    if (!empty($db)) {
        $data = $db->from('user')->select()->all();
    }else{
        http_response_code(500);
    }
    echo json_encode($data);
} else {
    http_response_code(405);
}
