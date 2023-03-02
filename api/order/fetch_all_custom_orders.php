<?php

require '../../config/config.php';
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // fetching all user list
    $data = $db->from('custom_order')->select()->all();
    echo json_encode($data);
} else {
    http_response_code(405);
}
