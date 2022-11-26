
<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user  =  $fun->verify_token();
    $request = file_get_contents("php://input");
    $request = json_decode($request);

    // request validator  
    $validation = $validator->make((array)$request, [
        'msg' => 'required|max:255',
        'rating' => 'required|integer',
        'pid' => 'required|integer',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        http_response_code(406);
        exit;
    }


    // updating review
    $result = $db->update('review')
        ->where('uid')->is($user['userid'])
        ->andWhere('pid')->is($request->pid)
        ->set(array(
            'uid' => $user['userid'],
            'pid' => $request->pid,
            'name' => $user['first_name'] . $user['last_name'],
            'msg' => $request->msg,
            'rating' => $request->rating
        ));

    echo json_encode(["status" => true, "msg" => "review updated"]);
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
    http_response_code(405);
}
