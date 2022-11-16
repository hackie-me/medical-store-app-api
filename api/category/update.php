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
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        exit;
    }

    // inserting records into database 
    try {
        $result = $db->update('category')
        ->where('id')->is($request->id)
        ->set(array(
            'name' => $request->name,
        ));
        echo json_encode(["status" => true, "msg" => "category updated"]);
    } catch (Exception $ex) {
        echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
        die();
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}
