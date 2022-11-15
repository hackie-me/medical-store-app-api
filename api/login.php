<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Rakit\Validation\Validator;

require '../config/config.php';
$date   = new DateTimeImmutable();
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'phone' => 'required',
        'password' => 'required|min:6',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        exit;
    }

    // creating new user
    $result_count = $db->from('users')
        ->where('phone')->is($request->phone)
        ->andWhere('password')->is($request->password)->select()
        ->count();
    if ($result_count == 1) {
        $result = $db->from('users')
        ->where('phone')->is($request->phone)
        ->andWhere('password')->is($request->password)->select()
        ->all();
        $request_data = [
            'iat'  => $date->getTimestamp(),
            'user_data' => $result
        ];

        // generating new auth token 
        $token = JWT::encode(
            $request_data,
            SECRET_KEY,
            'HS512'
        );

        // sending response 
        echo json_encode(["success" => true, "token" => $token, 'data' => $result]);
    } else {
        echo json_encode(["success" => false, "msg" => "invalid credentials"]);
    }
} else {
    echo json_encode(["success" => false, "msg" => "Method not allowed"]);
}


// $data = JWT::decode($token, new Key($secret_Key, 'HS512'));
