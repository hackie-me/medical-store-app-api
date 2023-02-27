<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'full_name' => 'required',
        'phone' => 'required|min:10|max:10',
        'password' => 'required|min:6',
        'confirm_password' => 'required|same:password',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // checking that data is unique or not 
    $uniq = $db->from('user')
        ->where('phone')->is($request->phone)
        ->select()
        ->count();
    if($uniq > 0){
        http_response_code(409);
        exit;
    }
    // creating new user
    $result = $db->insert(array(
        'full_name' => $request->full_name,
        'phone' => $request->phone,
        'password' => password_hash($request->password, PASSWORD_BCRYPT)
    ))->into('user');

    // Getting user info
    $result = $db->from('user')
        ->where('phone')->is($request->phone)->select()
        ->first();
    if ($request) {
        // set status code to 201
        http_response_code(201);
        // generating new user token
        $token = $fun->generate_token($result);
        // sending response
        echo $token;
    }else{
        http_response_code(500);
    }
} else {
    http_response_code(405);
}
