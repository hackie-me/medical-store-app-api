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
        'id' => 'required|integer',
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

    // inserting records into database 
    try {
        if (!empty($db)) {
            $result = $db->update('category')
            ->where('id')->is($request->id)
            ->set(array(
                'name' => $request->name,
            ));
            echo json_encode(["status" => true, "msg" => "category updated"]);
        }else{
            http_response_code(500);
        }
    } catch (Exception $ex) {
        echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
        http_response_code(500);
        die();
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
    http_response_code(405);
}
