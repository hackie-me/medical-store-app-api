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
    }else{
        http_response_code(500);
    }

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator
    $validation = $validator->make((array)$request, [
        'id' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors(); 
        echo json_encode($errors->firstOfAll()); 
        http_response_code(406); 
        exit; 
    }

    // deleting category from database  
    try {
        if (!empty($db) && isset($fun)) {
            // Get Product Thumbnail and Images
            $product = $db->from('products')->where('id')->is($request->id)->select()->first();

            // Delete Product Thumbnail and Images
            $fun->delete_media($product->thumbnail);
            $fun->bulk_delete_media($product->images);
            $db->from('products')->Where("category_id")->is($request->id)->delete();
            $db->from('category')->Where("id")->is($request->id)->delete();
            $fun->delete_media($request->thumbnail);
        }else{
            http_response_code(500);
        }
    } catch (Exception $ex) {
        echo json_encode($ex->getMessage());
        http_response_code(500);
        die();
    }
} else {
    http_response_code(405);
}
