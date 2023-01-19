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
    if (!empty($fun)) {
        $user = $fun->verify_token(true);
    }

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'id' => 'required|integer',
        'name' => 'required',
        'price' => 'required',
        'mrp' => 'required',
        'discount' => 'required',
        'quantity' => 'required',
        'brand_name' => 'required',
        'expiry_date' => 'required|date:d-m-Y',
        'thumbnail' => 'required',
        'images' => 'required',
        'ingredients' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // updating product
    try {
        $result = $db->update('products')
        ->where('id')->is($request->id)
        ->set(array(
            'name' => $request->name,
            'price' => $request->price,
            'mrp' => $request->mrp,
            'discount' => $request->discount,
            'quantity' => $request->quantity,
            'brand_name' => $request->brand_name != null ? $request->brand_name : 'Nilkanth Medical',
            'expiry_data' => $request->expiry_date,
            'thumbnail' => base64_encode($request->thumbnail),
            'images' => $request->images,
            'ingredients' => $request->ingredients,
        ));
        http_response_code(204);
    } catch (Exception $ex) {
        echo json_encode($ex->getMessage());
    }
} else {
    http_response_code(405);
}
