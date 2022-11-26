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
        'uid' => 'required|integer',
        'pid' => 'required|integer',
        'note' => 'required',
        'quantity' => 'required|integer',
        'street' => 'required',
        'area' => 'required',
        'pincode' => 'required|integer',
        'pdf' => 'required',
        'total' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        http_response_code(406);
        exit;
    }
    echo "validation success";
    exit;

    // inserting records into database 
    try {
        $result = $db->insert(array(
            'name' => $request->name,
            'uid' => $request->uid,
            'pid' => $request->pid,
            'note' => $request->note,
            'quantity' => $request->quantity,
            'street' => $request->street,
            'area' => $request->area,
            'pincode' => $request->pincode,
            'pdf' => $request->pdf,
            'total' => $request->total,
            'status' => "pending",
        ))->into('products');
        echo json_encode(["status" => true, "msg" => "product inserted"]);
        http_response_code(201);
    } catch (Exception $ex) {
        echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
        die();
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
    http_response_code(405);
}
