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
    $user = $fun->verify_token(true);

    $request = file_get_contents("php://input");
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

    // deleting category from database  
    try {
        $db->from('products')->Where("id")->is($request->id)->delete();
        echo json_encode(["Product Deleted"]);
    } catch (Exception $ex) {
        echo json_encode($ex->getMessage());
        http_response_code(500);
        die();
    }
} else {
    http_response_code(405);
}
