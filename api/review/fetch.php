
<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Authenticating user  
    if (!empty($fun)) {
        $fun->verify_token();
    }

    // fetching all reviews 
    if (!empty($db)) {
        $data = $db->from('review')->select()->all();
        echo json_encode($data);
    }else{
        http_response_code(500);
    }
} else {
    http_response_code(405);
}
