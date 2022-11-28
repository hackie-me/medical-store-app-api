<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $request = getRequestData($validator);

    // Authenticating 
    if (!empty($db) && !empty($fun)) {
        $result = $db->from('users')
            ->where('phone')->is($request->phone)->select()
            ->first();
        if ($result) {
            if (password_verify($request->password, $result['password'])) {
                // generating new user_auth token
                $token = $fun->generate_token($result);
                // sending response
                echo json_encode(["status" => true, "token" => $token]);
            } else {
                echo json_encode(["status" => false, "msg" => "Invalid password"]);
                http_response_code(400);
            }
        } else {
            echo json_encode(["status" => false, "msg" => "invalid credentials"]);
            http_response_code(400);
        }
    }


} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
    http_response_code(405);
}
