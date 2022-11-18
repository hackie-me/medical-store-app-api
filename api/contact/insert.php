<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user  =  $fun->verify_token();
    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'image' => 'required|max:255',
        'subject' => 'required',
        'msg' => 'required',
        'phone' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        exit;
    }

    // Inserting new contact request 
    $result = $db->insert(array(
        'image' => base64_encode($request->image),
        'subject' => $request->subject,
        'msg' => $request->msg,
        'phone' => $request->phone,
    ))->into('offers');

    echo json_encode(["status" => true, "msg" => "Contact inserted"]);
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}
