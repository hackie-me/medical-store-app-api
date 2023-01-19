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
    if (!empty($fun)) {
        $user = $fun->verify_token();
    }

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'id' => 'required|integer',
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
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // updating product
    try {
        if (!empty($db)) {
            $result = $db->update('products')
            ->where('id')->is($request->id)
            ->set(array(
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
            ));
        }
        http_response_code(204);
    } catch (Exception $ex) {
        echo json_encode($ex->getMessage());
        http_response_code(500);
    }
} else {
    http_response_code(405);
}
