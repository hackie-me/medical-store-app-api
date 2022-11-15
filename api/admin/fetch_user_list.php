<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $request = apache_request_headers();
    $token = explode(" ", $request['Authorization']);
    $token = $token[1];
    $data = JWT::decode($token, new Key(SECRET_KEY, 'HS512'));
    $data = (array)$data->data;
    if (password_verify($data['password'], $result->password)) {
        $data = $db->from('users')->select()->all();
        // sending response 
        echo json_encode(["status" => true, "data" => $data]);
    } else {
        echo json_encode(["status" => false, "msg" => "Invalid password"]);
    }
    echo json_encode(["status" => true, "data" => $data]);
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}
