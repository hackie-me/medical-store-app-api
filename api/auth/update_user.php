<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Authenticating user  
    $request = apache_request_headers();
    $token = explode(" ", $request['Authorization']);
    $token = $token[1]; 
    $authUser = JWT::decode($token, new Key(SECRET_KEY, 'HS512'));
    $authUser = (array)$authUser->data[0];

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
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        exit;
    }


    // Updating user
    $result = $db->update('users')
        ->where('userid')->is($authUser['userid'])
        ->set(array(
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'phone' => $request->phone,
            'username' => $request->username,
            'email' => $request->email,
        ));

    // Getting user info 
    $result = $db->from('users')
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
