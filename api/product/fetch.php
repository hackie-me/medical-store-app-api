<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // fetching category data
    $data = $db->from('products')->select()->all();
    foreach ($data as $key => $value) {
        if (empty($value['thumbnail'])) {
            $data[$key]['thumbnail'] = "https://source.unsplash.com/random?{$value['name']}";
        }
    }
    echo json_encode($data);
} else {
    
    http_response_code(405);
}
