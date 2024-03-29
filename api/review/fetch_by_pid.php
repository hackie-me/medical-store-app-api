
<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Authenticating user  
    if (!empty($fun)) {
        $fun->verify_token();
    }
    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator 
    $validation = $validator->make((array)$request, [
        'pid' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }
    // fetching review by product id  
    if (!empty($db)) {
        $data = $db->from('review')->where('pid')->is($request->pid)->select()->all();
        echo json_encode($data);
    }else{
        http_response_code(500);
    }
} else {
    
    http_response_code(405);
}
