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
        'id' => 'required|integer',
        'name' => 'required',
        'phone' => 'required',
        'email' => 'required',
        'address' => 'required',
        'city' => 'required',
        'state' => 'required',
        'pincode' => 'required|integer',
        'product_name' => 'required',
        'brand_name' => 'required',
        'quantity' => 'required|integer',
        'notes' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // updating order
    try {
        $result = $db->update('products')
            ->where('id')->is($request->id)
            ->set(array(
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'quantity' => $request->quantity,
                'uid' => $user['userid'],
                'updated_at' => date('Y-m-d H:i:s'),
            ));
        http_response_code(204);
    } catch (Exception $ex) {
        echo json_encode($ex->getMessage());
        http_response_code(500);
    }
} else {
    http_response_code(405);
}
