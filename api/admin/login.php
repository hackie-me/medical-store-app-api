<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;

if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

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
        'email' => 'required',
        'password' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }
    return $request;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $request = getRequestData($validator);

    // Authenticating
    $result = null;
    $result_count = 0;
    $result = $db->from('admin')
        ->where('email')->is($request->email)->select()
        ->first();
    $result_count = $db->from('admin')
        ->where('email')->is($request->email)->select()
        ->count();

    if (!$result && $result_count != 1) {
        http_response_code(401);
    }
    if (password_verify($request->password, $result['password'])) {
        $token = null;
        // generating new user token
        $token = $fun->generate_token($result);
        // sending response
        echo $token;
    } else {
        http_response_code(401);
    }
} else {
    http_response_code(405);
}
