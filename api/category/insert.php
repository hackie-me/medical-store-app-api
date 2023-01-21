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
        'name' => 'required',
        'description' => 'nullable',
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
    $uniq = $db->from('category')->where("name")->is($request->name)->select()->count();
    if ($uniq > 0) {
        http_response_code(409);
        exit;
    }
    // inserting records into database
    try {
        $result = $db->insert(array(
            'name' => $request->name,
            'description' => $request->description ?? null,
        ))->into('category');
        http_response_code(201);
        // get last insert id and return it
        echo $db->getConnection()->getPDO()->lastInsertId();
    } catch (Exception $ex) {
        http_response_code(500);
        echo json_encode($ex->getMessage());
        die();
    }
} else {
    http_response_code(405);
}
