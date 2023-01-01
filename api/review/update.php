
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
        'required|max:255',
        'rating' => 'required|integer',
        'pid' => 'required|integer',
        'msg' => 'required|max:255',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode($errors->firstOfAll());
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
            http_response_code(204);
        }else{
            http_response_code(500);
        }
    }
} else {
    http_response_code(405);
}
