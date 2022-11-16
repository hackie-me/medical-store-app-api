<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Opis\Database\SQL\Where;
use Rakit\Validation\Validator;

require '../../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Authenticating user  
    try {
        $request = apache_request_headers();
        $token = explode(" ", $request['Authorization']);
        $token = $token[1];
        $authUser = JWT::decode($token, new Key(SECRET_KEY, 'HS512'));
        $authUser = (array)$authUser->data;
    } catch (\Exception $ex) {
        echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
        die();
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
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        exit;
    }

    // deleting category from database  
    try {
        $db->from('products')->Where("category_id")->is($request->id)->delete();
        $db->from('category')->Where("id")->is($request->id)->delete();
        echo json_encode(["status" => true, "msg" => "Category Deleted"]);
    } catch (Exception $ex) {
        echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
        die();
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}
