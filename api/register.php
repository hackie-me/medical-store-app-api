<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require '../config/config.php';
require_once '../vendor/autoload.php';
$date   = new DateTimeImmutable();

$request = file_get_contents("php://input");

$request = json_decode($request);

$request_data = [
    'iat'  => $date->getTimestamp(),
    'iss'  => $domainName,
    'userName' => 'myUsername',
];

$token = JWT::encode(
    $request_data,
    $secret_Key,
    'HS512'
);


$data = JWT::decode($token, new Key($secret_Key, 'HS512'));


echo json_encode(["success" => true, "token" => $token]);
