<?php
require '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Verifying user token
    $data = null;
    if (!empty($fun)) {
        $data = $fun->verify_token(true);
    }else{
        http_response_code(500);
    }
    echo json_encode($data);
} else {
    
    http_response_code(405);
}
