<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($fun)) {
        $user  =  $fun->verify_token();
    }
    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'required|max:255',
        'rating' => 'required|integer',
        'pid' => 'required|integer',
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
    if (!empty($db)) {
        /** @var String $user */
        $uniq = $db->from('review')
            ->where('uid')->is($user[0]) // userid
            ->andWhere('pid')->is($request->pid)
            ->select()
            ->count();
    }

    if (!empty($uniq)) {
        if ($uniq > 0) {
            echo json_encode("You have already placed review for this product");
            exit;
        }
    }

    // creating new user
    if (!empty($db)) {
        $result = $db->insert(array(
            'uid' => $user[0], // userid
            'pid' => $request->pid,
            'name' => $user[1] . $user[2], // first_name, last_name
            $request->msg,
            'rating' => $request->rating,
        ))->into('review');
        http_response_code(201);
    }else{
        http_response_code(500);
    }
} else {
    http_response_code(405);
}
