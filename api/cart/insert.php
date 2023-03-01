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
        'price' => 'required'
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // checking if product already exists in cart
    $data = $db->from('cart')->where("uid")->is($user['userid'])->where("pid")->is($request->pid)->select()->all();
    if (count($data) > 0) {
        http_response_code(406);
        echo json_encode("Product already exists in cart");
        die();
    }

    // inserting records into database
    try {
        $result = $db->insert(array(
            'uid' => $user['userid'],
            'pid' => $request->pid,
            'quantity' => "1",
            'price' => $request->price,
        ))->into('cart');
        http_response_code(201);
        // get last insert id and return it
        echo $db->getConnection()->getPDO()->lastInsertId();
    } catch (Exception $ex) {
        http_response_code(500);
        echo json_encode($ex->getMessage());
        die();
    }
} else {
    http_response_code(405);
}
