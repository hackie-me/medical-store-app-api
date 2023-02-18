<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator
    $validation = $validator->make((array)$request, [
        'uid' => 'required|numeric|min:1',
        'pid' => 'required|numeric|min:1',
        'name' => 'required|min:3|max:50',
        'msg' => 'required|min:3|max:500',
        'rating' => 'required|numeric|min:1|max:5',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    try {
        // if review already exists
        $data = $db->from('review')
            ->where('uid')->is($request->uid)
            ->where('pid')->is($request->pid)
            ->select()
            ->all();
        if (!empty($data)) {
            echo json_encode('Review already exists!');
            http_response_code(406);
            exit;
        }

        // inserting records into database
        $result = $db->insert(array(
            'uid' => $request->uid,
            'pid' => $request->pid,
            'name' => $request->name,
            'msg' => $request->msg,
            'rating' => $request->rating ? $request->rating : 0,
        ))->into('review');
        http_response_code(201);
    } catch (Exception $ex) {
        echo json_encode($ex->getMessage());
        die();
    }
} else {
    http_response_code(405);
}
