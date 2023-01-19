<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($fun)) {
        $user  =  $fun->verify_token(true);
    }else{
        http_response_code(500);
    }
    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'name' => 'required',
        'images' => 'required|max:255',
        'offer' => 'required|max:255',
        'price' => 'required|max:255',
        'description' => 'required|max:255',
        'start_date' => 'required|max:255',
        'end_date' => 'required|max:255',
        'discount' => 'required',
        'discount_price' => 'required',
        'category' => 'required',
        'brand' => 'required',
        'code' => 'required',
        'status' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // creating new offer
    if (!empty($db) && !empty($fun)) {
        // uploading offer images
        $image = $request->image;
        $fun->upload_image($image);

        $result = $db->insert(array(
            'name' => $request->name,
            'images' => $request->image,
            'offer' => $request->offer,
            'price' => $request->price,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'discount' => $request->discount,
            'discount_price' => $request->discount_price,
            'category' => $request->category,
            'brand' => $request->brand,
            'code' => $request->code,
            'status' => $request->status
        ))->into('offer');
        echo json_encode(["Offer inserted"]);
        http_response_code(201);
    }else{
        http_response_code(500);
    }

} else {
    http_response_code(405);
}
