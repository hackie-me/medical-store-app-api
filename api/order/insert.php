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
        'note' => 'required',
        'address' => 'required',
        'total' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    try {

        // Get all products from cart by user id
        $cart = $data = $db->from('cart')->where("uid")->is($user['userid'])->select()->all();

        // Inserting all products from cart to order table
        foreach ($cart as $key) {
            // get the product price
            $product = $db->from('products')->where("id")->is($key['pid'])->select()->first();
            $price = $product['price'];
            $total = $price * $key['quantity'];
            $result = $db->insert(array(
                "uid" => $user['userid'],
                "pid" => $key['pid'],
                "note" => $request->note,
                "quantity" => $key['quantity'],
                "address" => $request->address,
                "total" => $total,
            ))->into('orders');
            // Update cart table by deleting all products from cart
            $db->from('cart')->where("uid")->is($user['userid'])->delete();
        }
        http_response_code(201);
    } catch (Exception $ex) {
        echo json_encode($ex->getMessage());
        die();
    }
} else {
    http_response_code(405);
}
