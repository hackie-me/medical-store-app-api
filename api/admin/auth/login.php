<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Rakit\Validation\Validator;

require '../../../config/config.php';
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

    // Authenticating 
    $result = $db->from('admin')
        ->where('phone')->is($request->phone)->select()
        ->first();

    if ($result == true) {
        if (password_verify($request->password, $result->password)) {
            $request_data = [
                'iat'  => $date->getTimestamp(),
                'data' => $result
            ];

            // generating new auth token 
            $token = JWT::encode(
                $request_data,
                SECRET_KEY,
                'HS512'
            );

            // sending response 
            echo json_encode(["status" => true, "token" => $token]);
        } else {
            echo json_encode(["status" => false, "msg" => "Invalid password"]);
        }
    } else {
        echo json_encode(["status" => false, "msg" => "invalid credentials"]);
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}


// $data = JWT::decode($token, new Key($secret_Key, 'HS512'));
