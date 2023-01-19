<?php

use Rakit\Validation\Validator;

require '../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // getting user info from old token
    if (!empty($fun) && !empty($db)) {
        $user = $fun->verify_token(true);
        if ($user == null) {
            http_response_code(403);
            exit();
        }
        // Select user from database
        $result = $db->from('admin')
            ->where('id')->is($user[0])
            ->select()
            ->first();

        // generating new user token
        if ($result) {
            // sending response
            http_response_code(200);
            echo $fun->generate_token($result);
        }
    } else {
        http_response_code(500);
    }
}else{
    http_response_code(405);
}
