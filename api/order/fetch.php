<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Authenticating user  
    if (!empty($fun)) {
        // TODO: order can be fetched by both admin and user
        $fun->verify_token();
    }else{
        http_response_code(500);
    }

    // fetching category data
    $data = null;
    if (!empty($db)) {
        $data = $db->from('order')->select()->all();
    }else{
        http_response_code(500);
    }
    echo json_encode($data);
} else {
    http_response_code(405);
}
