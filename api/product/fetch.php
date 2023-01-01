<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Authenticating user  
    if (!empty($fun)) {
        $fun->verify_token();
    }else{
        http_response_code(500);
    }

    // fetching category data  
    if (!empty($db)) {
        $data = $db->from('products')->select()->all();
        echo json_encode($data);
    }else{
        http_response_code(500);
    }
} else {
    
    http_response_code(405);
}
