<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $user = $fun->verify_token();

    $request = file_get_contents("php://input");
    $request = json_decode($request);
    // request validator
    $validation = $validator->make((array)$request, [
        'pid' => 'required',
    ]);
    $validation->validate();
    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // checking if product is already in cart
    $data = $db->from('cart')
        ->where("uid")->is($user['userid'])
        ->andWhere("pid")->is($request->pid)
        ->select()->all();
    if (count($data) > 0) {
        http_response_code(406);
        echo json_encode("Product already exists in cart");
    }else{
        http_response_code(404);
        echo json_encode("Product not in cart");
    }
    die();
} else {
    http_response_code(405);
}
