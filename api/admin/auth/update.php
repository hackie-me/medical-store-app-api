<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Rakit\Validation\Validator;

require '../../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = null;
    if (!empty($fun)) {
        $user = $fun->verify_token();
    }else{
        http_response_code(500);
    }
    if($user == null){
        http_response_code(403);
        exit();
    }

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'name' => 'required',
        'phone' => 'required|min:10|max:10',
        'email' => 'required|email',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        http_response_code(406);
        exit;
    }


    // Updating user
    if (!empty($db) && !empty($fun)) {
        $result = $db->update('admin')
            ->where('id')->is($user[0])
            ->set(array(
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
            ));
        // Getting user info
        $result = $db->from('admin')
            ->where('phone')->is($request->phone)->select()
            ->first();

        // generating new user_auth token
        if ($result) {
            // generating new user_auth token
            $token = $fun->generate_token($result);
            // sending response
            echo json_encode(["status" => true, "token" => $token]);
        }
    }else{
        http_response_code(500);
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
    http_response_code(405);
}
