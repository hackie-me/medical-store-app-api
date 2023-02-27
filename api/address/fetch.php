<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $user = $fun->verify_token();

    // fetching all category data
    $data = $db->from('address')->where("uid")->is($user['userid'])->select()->all();
    echo json_encode($data);

} else {
    http_response_code(405);
}
