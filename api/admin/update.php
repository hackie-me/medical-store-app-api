<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user = $fun->verify_token(true);

    if ($user == null) {
        http_response_code(403);
        exit();
    }

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'name' => 'required',
        'phone' => 'required',
        'email' => 'required|email',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // Updating user
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

    echo "sdfsdf";
    print_r($result);

    // generating new user token
    if ($result) {
        // sending response
        http_response_code(204);
        $token = $fun->generate_token($result);
        echo "token";
        echo $token;
    }
} else {
    http_response_code(405);
}
