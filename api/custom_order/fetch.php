<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // fetching all orders
    $data = $db->from('order')->select()->all();
    echo json_encode($data);
} else {
    http_response_code(405);
}
