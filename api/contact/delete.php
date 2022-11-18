
<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Authenticating user  
    $user = $fun->verify_token();

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

    // deleting contact from database  
    try {
        $db->from('contact')->Where("id")->is($request->id)->delete();
        echo json_encode(["status" => true, "msg" => "Contact Deleted"]);
    } catch (Exception $ex) {
        echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
        die();
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}
