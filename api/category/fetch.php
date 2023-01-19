<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // fetching all category data  
    $data = $db->from('category')->select()->all();
    foreach ($data as $key => $value) {
        $data[$key]['image'] = json_decode($value['images']);
    }
    echo json_encode($data);
} else {
    http_response_code(405);
}
