<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Rakit\Validation\Validator;

require '../../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Authenticating user  
    $request = apache_request_headers();
    $token = explode(" ", $request['Authorization']);
    $token = $token[1];
    $authUser = JWT::decode($token, new Key(SECRET_KEY, 'HS512'));
    $authUser = (array)$authUser->data;

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'name' => 'required',
        'phone' => 'required',
        'email' => 'required|email',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        exit;
    }


    // Updating user
    $result = $db->update('admin')
        ->where('id')->is($authUser['id'])
        ->set(array(
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ));

    // Getting user info 
    $result = $db->from('admin')
        ->where('phone')->is($request->phone)->select()
        ->first();

    // generating new auth token 
    if ($request == true) {
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
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}
