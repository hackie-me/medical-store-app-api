<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Authenticating user  
    if (!empty($fun)) {
        $fun->verify_token();
    }

    $request = file_get_contents('php://input');
    $request = json_decode($request);
    // request validator 
    $validation = $validator->make((array)$request, [
        'id' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // fetching category data  
    if (!empty($db)) {
        $data = $db->from('order')->where('id', $request->id)->select()->all();
        echo json_encode($data);
    } else {
        http_response_code(500);
    }
} else {
    http_response_code(405);
}
