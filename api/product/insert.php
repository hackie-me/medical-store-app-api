<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = null;
    // Authenticating user   
    if (!empty($fun)) {
        $user = $fun->verify_token();
    }else{
        http_response_code(500);
    }

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'name' => 'required',
        'price' => 'required',
        'mrp' => 'required',
        'discount' => 'required',
        'quantity' => 'required',
        'brand_name' => 'required',
        'expiry_date' => 'required|date:d-m-Y',
        'thumbnail' => 'required|extension:0,500K,png,jpeg',
        'images' => 'required|array',
        'images.*' => 'uploaded_file:0,500K,png,jpeg',
        'ingredients' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        http_response_code(406);
        exit;
    }

    // inserting records into database 
    try {
        if (!empty($db)) {
            $result = $db->insert(array(
                'name' => $request->name,
                'price' => $request->price,
                'mrp' => $request->mrp,
                'discount' => $request->discount,
                'quantity' => $request->quantity,
                'brand_name' => !($request->brand_name == null) ? $request->brand_name : 'Nilkanth Medical',
                'expiry_data' => $request->expiry_date,
                'thumbnail' => base64_encode($request->thumbnail),
                'images' => $request->images,
                'ingredients' => $request->ingredients,
            ))->into('product');
            echo json_encode(["status" => true, "msg" => "product inserted"]);
            http_response_code(201);
        }
    } catch (Exception $ex) {
        echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
        die();
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
    http_response_code(405);
}
