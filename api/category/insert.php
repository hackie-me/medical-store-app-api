<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


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
        'name' => 'required',
        'description' => 'required',
        'image' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // checking that data is unique or not 
    if (!empty($db)) {
        $uniq = $db->from('category')->where("name")->is($request->name)->select()->count();
        if ($uniq > 0) {
            echo json_encode(["category already exist"]);
            exit;
        }

        // inserting records into database
        try {
            $result = $db->insert(array(
                'name' => $request->name,
                'description' => $request->description,
                'image' => $request->image
            ))->into('category');
            echo json_encode(["category inserted"]);
            http_response_code(201);
        } catch (Exception $ex) {
            echo json_encode($ex->getMessage());
            die();
        }
    }
} else {
    
    http_response_code(405);
}
