<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
$user = null;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // verifying user token
    $user = $fun->verify_token();

    $request = file_get_contents("php://input");
    $request = json_decode($request);
    // request validator  
    $validation = $validator->make((array)$request, [
        'first_name' => 'required',
        'last_name' => 'required',
        'address' => 'required',
        'city' => 'required',
        'state' => 'required',
        'zip' => 'required|min:6',
        'phone' => 'required|min:10|max:10',
        'email' => 'required|email',
        'username' => 'required',
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
    if (!empty($db) && !empty($fun) && !empty($user)) {
        $result = $db->update('user')
            ->where('userid')->is($user[0]) // ['userid']
            ->set(array(
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
                'phone' => $request->phone,
                'username' => $request->username,
                'email' => $request->email,
            ));
        // Getting user info
        $result = $db->from('user')
            ->where('phone')->is($request->phone)->select()
            ->first();

        // generating new user token
        if ($result) {
            // generating new user token
            $token = $fun->generate_token($result);

            // sending response
            echo json_encode($token);
        }else{
            http_response_code(500);
        }
    }
} else {
    
    http_response_code(405);
}
