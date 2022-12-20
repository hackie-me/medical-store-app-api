<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Rakit\Validation\Validator;

require '../../../config/config.php';
$validator = new Validator;

/**
 * @param Validator $validator
 * @return mixed
 */
function getRequestData(Validator $validator): mixed
{
    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator
    $validation = $validator->make((array)$request, [
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        http_response_code(406);
        exit;
    }
    return $request;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $request = getRequestData($validator);

    // Authenticating
    $result = null;
    if (!empty($db)) {
        $result = $db->from('admin')
            ->where('email')->is($request->email)->select()
            ->first();
    }else{
        http_response_code(500);
    }

    if (!$result) {
        echo json_encode(["status" => false, "msg" => "invalid credentials"]);
        http_response_code(400);
    } else {
        if (password_verify($request->password, $result['password'])) {
            $token = null;
            // generating new user_auth token
            if (!empty($fun)) {
                $token = $fun->generate_token($result);
            }else{
                http_response_code(500);
            }
            // sending response
            echo json_encode(["status" => true, "token" => $token]);
        } else {
            echo json_encode(["status" => false, "msg" => "Invalid password"]);
            http_response_code(400);
        }
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
    http_response_code(405);
}
