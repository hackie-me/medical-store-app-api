<?php

use Rakit\Validation\Validator;

require '../../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Authenticating user  
    $user = $fun->verify_token();

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
        'thumbnail' => 'required|extension:0,500K,png,jpeg',
        'images.*' => 'required|array',
        'images.*' => 'required|uploaded_file:0,500K,png,jpeg',
        'ingredients' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        exit;
    }

    // updating products  
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
        echo json_encode(["status" => true, "msg" => "category updated"]);
    } catch (Exception $ex) {
        echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
        die();
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}
