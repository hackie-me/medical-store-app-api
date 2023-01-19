<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($fun)) {
        $user  =  $fun->verify_token();
    }else{
        http_response_code(500);
    }
    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'name' => 'required',
        'phone' => 'required', 
        'images' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // Inserting new contact request 
    if (!empty($db)) {
        $result = $db->insert(array(
            'name' => $request->name,
            'phone' => $request->phone,
            'message' => $request->msg ?? '',
            'images' => base64_encode($request->image),
        ))->into('contact');
        http_response_code(201);
    }else{
        http_response_code(500);
    }

} else {
    http_response_code(405);
}
