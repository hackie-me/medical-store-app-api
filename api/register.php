<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require '../config/config.php';
$date   = new DateTimeImmutable();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request = file_get_contents("php://input");
    $request = json_decode($request);


    $name = $request->name;
    $email = $request->email;
    $mobile = $request->mobile;
    


 
    
$request_data = [
    'iat'  => $date->getTimestamp(),
    'userName' => 'myUsername',
];

$token = JWT::encode(
    $request_data,
    $secret_Key,
    'HS512'
);
}else{
    echo json_encode(["success" => false, "msg" => "Method not allowed"]);
}


// $data = JWT::decode($token, new Key($secret_Key, 'HS512'));


