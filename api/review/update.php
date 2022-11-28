
<?php

use Rakit\Validation\Validator;

require '../../config/config.php';
$validator = new Validator;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!empty($fun)) {
        $user  =  $fun->verify_token();
    }
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
    if (!empty($db)) {
        if (!empty($user)) {
            $result = $db->update('review')
                ->where('uid')->is($user[0]) // ['userid']
                ->andWhere('pid')->is($request->pid)
                ->set(array(
                    'uid' => $user[0], // ['userid']
                    'pid' => $request->pid,
                    'name' => $user[1] . $user[2],
                    'msg' => $request->msg,
                    'rating' => $request->rating
                ));
        }else{
            echo json_encode(['status' => false, 'msg' => 'Missing User data']);
            http_response_code(500);
        }
    }
    echo json_encode(["status" => true, "msg" => "review updated"]);
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
    http_response_code(405);
}
