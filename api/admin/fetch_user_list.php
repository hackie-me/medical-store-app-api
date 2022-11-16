<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $fun->verify_token();
    // fetching all users list 
    $data = $db->from('users')->select()->all();
    echo json_encode(["status" => true, "data" => $data]);
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}
