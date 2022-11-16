<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user  =  $fun->verify_token();
    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'msg' => 'required|max:255',
        'rating' => 'required|integer',
        'pid' => 'required|integer',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        exit;
    }

    // checking that data is unique or not 
    $uniq = $db->from('review')
        ->where('uid')->is($user['userid'])
        ->andWhere('pid')->is($request->pid)
        ->select()
        ->count();

    if ($uniq > 0) {
        echo json_encode(["success" => false, "msg" => "You have already placed review for this product"]);
        exit;
    }

    // creating new user
    $result = $db->insert(array(
        'uid' => $user['userid'],
        'pid' => $request->pid,
        'name' => $user['first_name'] . $user['last_name'],
        'msg' => $request->msg,
        'rating' => $request->rating,
    ))->into('review');

    echo json_encode(["status" => true, "msg" => "review inserted"]);
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}
