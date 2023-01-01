
<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Authenticating user
    if (!empty($fun)) {
        $user = $fun->verify_token();
    }

    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator
    $validation = $validator->make((array)$request, [
        'id' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // deleting category from database
    try {
        if (!empty($db)) {
            $db->from('user')->Where("userid")->is($request->id)->delete();
        }
        echo json_encode(["User Deleted"]);
    } catch (Exception $ex) {
        echo json_encode($ex->getMessage());
        die();
    }
} else {
    http_response_code(405);
}
