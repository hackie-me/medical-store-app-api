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
        'name' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        exit;
    }

    // checking that data is unique or not 
    $uniq = $db->from('category')->where("name")->is($request->name)->select()->count();
    if ($uniq > 0) {
        echo json_encode(["success" => false, "msg" => "category already exist"]);
        exit;
    }

    // inserting records into database 
    try {
        $result = $db->insert(array(
            'name' => $request->name
        ))->into('category');
        echo json_encode(["status" => true, "msg" => "category inserted"]);
    } catch (Exception $ex) {
        echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
        die();
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}
