<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $request = getRequestData($validator);

    // Authenticating 
    if (!empty($db) && !empty($fun)) {
        $result = $db->from('user')
            ->where('phone')->is($request->phone)->select()
            ->first();
        if ($result) {
            if (password_verify($request->password, $result['password'])) {
                // generating new user token
                $token = $fun->generate_token($result);
                // sending response
                echo json_encode($token);
            } else {
                http_response_code(400);
            }
        } else {
            http_response_code(400);
        }
    }
} else {
    http_response_code(405);
}
