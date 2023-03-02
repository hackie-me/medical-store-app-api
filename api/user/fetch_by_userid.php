<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $userid = $_GET['userid'];
    // fetching review by product id  
    $data = $db->from('user')->where('userid')->is($userid)->select()->all();
    echo json_encode($data);
} else {
    http_response_code(405);
}
