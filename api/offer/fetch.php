
<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // fetching all offer
    if (!empty($db)) {
        $data = $db->from('offer')->select()->all();
        echo json_encode($data);
    }else{
        http_response_code(500);
    }
} else {
    http_response_code(405);
}
