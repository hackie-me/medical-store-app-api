<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'name' => 'required',
        'phone' => 'required|min:10|max:10',
        'email' => 'required|email',
        'password' => 'required|min:6',
        'confirm_password' => 'required|same:password'
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // checking that data is unique or not
    $uniq = null;
    if (!empty($db) && !empty($fun)) {
        $uniq = $db->from('admin')
            ->where('phone')->is($request->phone)
            ->orWhere('email')->is($request->email)
            ->select()
            ->count();
        if ($uniq > 0) {
            http_response_code(409);
            exit;
        }else{
            // creating new user
            $result = $db->insert(array(
                'name' => $request->name,
                'image' => "https://via.placeholder.com/100",
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => password_hash($request->password, PASSWORD_BCRYPT)
            ))->into('admin');

            // Getting user info
            $result = $db->from('admin')
                ->where('phone')->is($request->phone)->select()
                ->first();

            // sending response and generating token
            http_response_code(201);
            echo $fun->generate_token($result);
        }
    }else{
        http_response_code(500);
    }
} else {
    http_response_code(405);
}
