
<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = null;
    if (!empty($fun)) {
        $user  =  $fun->verify_token(true);
    }else{
        http_response_code(500);
    }
    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator  
    $validation = $validator->make((array)$request, [
        'images' => 'required|max:255',
        'discount' => 'required',
        'code' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
        http_response_code(406);
        exit;
    }

    // updating offer
    if (!empty($db)) {
        $result = $db->update('offer')
            ->where('uid')->is($user[0]) // ['userid']
            ->andWhere('pid')->is($request->pid)
            ->set(array(
                'images' => base64_encode($request->image),
                'discount' => $request->discount,
                'code' => $request->code
            ));
        http_response_code(204);
    }

} else {
    http_response_code(405);
}
