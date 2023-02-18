<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // request validator
    $validation = $validator->make($_GET, [
        'id' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // fetching category data
    $data = $db->from('products')
        ->where('id')->is($_GET['id'])
        ->select()
        ->all();
    foreach ($data as $key => $value) {
        if (empty($value['image'])) {
            $data[$key]['thumbnail'] = "https://source.unsplash.com/random?{$value['name']}";
        }
    }
    echo json_encode($data[0]);
} else {

    http_response_code(405);
}
