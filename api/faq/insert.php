<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Verifying token
    $user = $fun->verify_token();

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator
    $validation = $validator->make((array)$request, [
        'question' => 'required',
        'answer' => 'required',
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
    $db->insert(array(
        'question' => $request->question,
        'answer' => $request->answer,
    ))->into('faq');
    http_response_code(201);
} else {
    http_response_code(405);
}
