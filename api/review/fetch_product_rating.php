<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator
    $validation = $validator->make((array)$request, [
        'pid' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // check if product review is exist or not
    $review = $db->from('review')
        ->where('pid')->is($request->pid)
        ->select()
        ->all();

    if (empty($review)) {
        echo json_encode(['message' => 'No review found']);
        http_response_code(404);
        exit;
    }

    // fetching category data
    $data = $db->from('review')
        ->where('pid')->is($request->pid)
        ->select()
        ->all();

    // calculating average rating must be between in 1 to 5
    $rating = 0;
    foreach ($data as $key => $value) {
        $rating += $value['rating'];
    }
    $rating = $rating / count($data);
    $rating = round($rating, 1);
    if ($rating > 5) {
        $rating = 5;
    }
    if ($rating < 1) {
        $rating = 1;
    }

    // send rating as response
    echo json_encode([$rating]);

} else {

    http_response_code(405);
}
