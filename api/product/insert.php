<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = null;
    // Authenticating user   
    if (!empty($fun)) {
        $user = $fun->verify_token(true);
    }else{
        http_response_code(500);
    }

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'name' => 'required',
        'description' => 'required',
        'price' => 'required',
        'mrp' => 'required',
        'discount' => 'required',
        'brand_name' => 'required',
        'expiry_date' => 'required|date:d-m-Y',
        'thumbnail' => 'required',
        'images' => 'required|array',
        'images.*' => 'required',
        'ingredients' => 'required',
        'status' => 'required',
        'unit' => 'required',
        'stock' => 'required',
        'category_id' => 'required',
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
        if (!empty($db) && isset($fun)) {
            $result = $db->insert(array(
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'mrp' => $request->mrp,
                'discount' => $request->discount,
                'brand_name' => !($request->brand_name == null) ? $request->brand_name : 'Nilkanth Medical',
                'expiry_date' => $request->expiry_date,
                'thumbnail' => $fun->upload_image($request->thumbnail, 'product/thumbnail'),
                'images' => json_encode($fun->bulk_upload_image($request->images, 'product/images')),
                'ingredients' => $request->ingredients,
                'status' => $request->status,
                'unit' => $request->unit,
                'stock' => $request->stock,
                'category_id' => $request->category_id,
            ))->into('products');
            http_response_code(201);
        }
    } catch (Exception $ex) {
        echo json_encode($ex->getMessage());
        die();
    }
} else {
    
    http_response_code(405);
}
