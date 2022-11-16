<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Rakit\Validation\Validator;

require '../../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user = $fun->verify_token();

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'name' => 'required',
        'phone' => 'required|min:10|max:10',
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
        ->where('id')->is($user['id'])
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
    if ($result == true) {
        // generating new auth token 
        $token = $fun->generate_token($result);
        // sending response 
        echo json_encode(["status" => true, "token" => $token]);
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}
