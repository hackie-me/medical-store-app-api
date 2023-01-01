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
        'id' => 'required|integer',
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

    // inserting records into database 
    try {
        if (!empty($db)) {
            $result = $db->update('category')
            ->where('id')->is($request->id)
            ->set(array(
                'name' => $request->name,
                'description' => $request->description,
                'image' => $request->image
            ));
            http_response_code(204);
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
