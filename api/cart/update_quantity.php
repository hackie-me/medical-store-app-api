<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Authenticating user
    $user = $fun->verify_token();

    $request = file_get_contents("php://input");
    $request = json_decode($request);
    // request validator
    $validation = $validator->make((array)$request, [
        'pid' => 'required',
        'operation' => 'required'
    ]);
    $validation->validate();
    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }
    // inserting records into database
    try {
        if ($request->operation == 'add') {
            $db->update('cart')
                ->where('pid')
                ->is($request->pid)
                ->increment('quantity', 1);
            http_response_code(204);
        } else if ($request->operation == 'sub') {
            $db->update('cart')
                ->where('pid')
                ->is($request->pid)
                ->decrement('quantity', 1);
            http_response_code(204);
        } else {
            http_response_code(406);
        }
    } catch (Exception $ex) {
        http_response_code(500);
        echo json_encode($ex->getMessage());
        die();
    }
} else {
    http_response_code(405);
}
