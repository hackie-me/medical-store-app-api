<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Rakit\Validation\Validator;

require '../../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'name' => 'required',
        'phone' => 'required|min:10|max:10',
        'email' => 'required|email',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'password' => 'required|min:6',
        'confirm_password' => 'required|same:password'
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
    $uniq = null;
    if (!empty($db) && !empty($fun)) {
        $uniq = $db->from('admin')
            ->where('phone')->is($request->phone)
            ->orWhere('email')->is($request->email)
            ->select()
            ->count();
        if ($uniq > 0) {
            echo json_encode(["success" => false, "msg" => "admin already exist"]);
            exit;
        }else{
            // creating new user
            $result = $db->insert(array(
                'name' => $request->name,
                'image' => $fun->upload_image($request->image),
                'phone' => $request->phone,
                'email' => $request->email,
                'password' => password_hash($request->password, PASSWORD_BCRYPT)
            ))->into('admin');

            // Getting user info
            $result = $db->from('admin')
                ->where('phone')->is($request->phone)->select()
                ->first();

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
