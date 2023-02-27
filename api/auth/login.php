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
        'phone' => 'required',
        'password' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }


    // Authenticating 
    $result = $db->from('user')
        ->where('phone')->is($request->phone)->select()
        ->first();
    if ($result) {
        if (password_verify($request->password, $result['password'])) {
            // generating new user token
            $token = $fun->generate_token($result);
            // sending response
            echo $token;
        } else {
            http_response_code(400);
        }
    } else {
        http_response_code(400);
    }
} else {
    http_response_code(405);
}
