<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // fetching all category data  
    if (!empty($db)) {
        $data = $db->from('category')->select()->all();
        echo json_encode($data);
    }else{
        http_response_code(500);
    }
} else {
    http_response_code(405);
}
