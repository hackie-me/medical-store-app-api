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
        'first_name' => 'required',
        'last_name' => 'required',
        'address' => 'required',
        'city' => 'required',
        'state' => 'required',
        'zip' => 'required|min:6',
        'phone' => 'required',
        'email' => 'required|email',
        'username' => 'required',
        'password' => 'required|min:6',
        'confirm_password' => 'required|same:password',
        'avatar' => 'nullable',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        exit;
    }

    // Getting users file 
    $avatar = "https://api.multiavatar.com/stefan.svg";
    
    // creating new user
    $result = $db->insert(array(
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'address' => $request->address,
        'city' => $request->city,
        'state' => $request->state,
        'zip' => $request->zip,
        'phone' => $request->phone,
        'username' => $request->username,
        'email' => $request->email,
        'mail_hash' => hash('md5', $request->email),
        'password' => password_hash($request->password, PASSWORD_BCRYPT),
        'avatar' => $avatar,
    ))->into('users');

    $request_data = [
        'iat'  => $date->getTimestamp(),
    ];

    // generating new auth token 
    $token = JWT::encode(
        $request_data,
        SECRET_KEY,
        'HS512'
    );

    // sending response 
    echo json_encode(["success" => true, "token" => $token]);

} else {
    echo json_encode(["success" => false, "msg" => "Method not allowed"]);
}


// $data = JWT::decode($token, new Key($secret_Key, 'HS512'));
