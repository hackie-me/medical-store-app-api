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
        'id' => 'required|integer',
        'name' => 'required',
        'description' => 'required',
        'price' => 'required',
        'mrp' => 'required',
        'discount' => 'required',
        'brand_name' => 'required',
        'expiry_date' => 'required|date:d-m-Y',
        'ingredients' => 'required',
        'status' => 'required',
        'unit' => 'required',
        'quantity' => 'required',
        'category_id' => 'required'
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // Formatting date
    $request->expiry_date = date('d-m-Y', strtotime($request->expiry_date));

    // updating product
    try {
        $result = $db->update('products')
        ->where('id')->is($request->id)
        ->set(array(
            'name' => $request->name,
            'price' => $request->price,
            'mrp' => $request->mrp,
            'discount' => $request->discount,
            'stock' => $request->quantity,
            'brand_name' => $request->brand_name != null ? $request->brand_name : 'Nilkanth Medical',
            'expiry_date' => $request->expiry_date,
            'images' => json_encode(["https://picsum.photos/500/500", "https://picsum.photos/500/500", "https://picsum.photos/500/500", "https://picsum.photos/500/500"]),
            'ingredients' => $request->ingredients,
        ));
        http_response_code(204);
    } catch (Exception $ex) {
        echo json_encode($ex->getMessage());
    }
} else {
    http_response_code(405);
}
