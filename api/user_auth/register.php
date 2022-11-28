<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
        'password' => 'required|min:6',
        'confirm_password' => 'required|same:password',
        'avatar' => 'nullable',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        http_response_code(406);
        exit;
    }

    // checking that data is unique or not 
    if (!empty($db) && !empty($fun)) {
        $uniq = $db->from('users')
            ->where('phone')->is($request->phone)
            ->orWhere('email')->is($request->email)
            ->orWhere('username')->is($request->username)
            ->select()
            ->count();
        if($uniq > 0){
            echo json_encode(["success" => false, "msg" => "user already exist"]);
            exit;
        }
        // creating new user
        $result = $db->insert(array(
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'phone' => $request->phone,
            'username' => $request->username,
            'email' => $request->email,
            'mail_hash' => hash('md5', $request->email),
            'password' => password_hash($request->password, PASSWORD_BCRYPT)
        ))->into('users');

        // Getting user info
        $result = $db->from('users')
            ->where('phone')->is($request->phone)->select()
            ->first();
        if ($request) {
            // generating new user_auth token
            $token = $fun->generate_token($result);
            // sending response
            echo json_encode(["status" => true, "token" => $token]);
        }else{
            http_response_code(500);
        }
    }else{
        http_response_code(500);
    }
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
    http_response_code(405);
}
