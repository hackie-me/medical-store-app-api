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
        'name' => 'required',
        'phone' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6',
        'confirm_password' => 'required|same:password'
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        exit;
    }


    // creating new user
    $result = $db->insert(array(
        'name' => $request->name,
        'phone' => $request->phone,
        'email' => $request->email,
        'password' => password_hash($request->password, PASSWORD_BCRYPT)
    ))->into('admin');

    // Getting user info 
    $result = $db->from('admin')
        ->where('phone')->is($request->phone)->select()
        ->first();

    // generating new auth token 
    $request_data = [
        'iat'  => $date->getTimestamp(),
        'data' => $result
    ];
    $token = JWT::encode(
        $request_data,
        SECRET_KEY,
        'HS512'
    );
    // sending response 
    echo json_encode(["status" => true, "token" => $token]);
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}
