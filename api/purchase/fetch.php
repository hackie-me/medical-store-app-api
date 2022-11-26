<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Authenticating user  
    if (!empty($fun)) {
        $fun->verify_token();
    }else{
        http_response_code(500);
    }

    // fetching category data
    $data = null;
    if (!empty($db)) {
        $data = $db->from('purchase')->select()->all();
    }else{
        http_response_code(500);
    }
    echo json_encode(["status" => true, "data" => $data]);
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
    http_response_code(405);
}
