<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // verifying auth token 
    $user = $fun->verify_token();

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
        'phone' => 'required|min:10|max:10',
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
        ->where('userid')->is($user['userid'])
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
    if ($result == true) {
        // generating new auth token 
        $token = $fun->generate_token($result);

        // sending response 
        echo json_encode(["status" => true, "token" => $token]);
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}