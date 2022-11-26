<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        http_response_code(406);
        exit;
    }

    // checking that data is unique or not 
    if (!empty($db)) {
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
            http_response_code(201);
        } catch (Exception $ex) {
            echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
            die();
        }
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
    http_response_code(405);
}
