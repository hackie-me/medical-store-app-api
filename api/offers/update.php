
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
        'image' => 'required|max:255',
        'discount' => 'required',
        'code' => 'required',
    ]);

    $validation->validate();

    // handling request errors
    if ($validation->fails()) {
        $errors = $validation->errors();
        echo json_encode(["success" => false, "msg" => $errors->firstOfAll()]);
        exit;
    }

    // updating offers
    $result = $db->update('offers')
        ->where('uid')->is($user['userid'])
        ->andWhere('pid')->is($request->pid)
        ->set(array(
            'image' => base64_encode($request->image),
            'discount' => $request->discount,
            'code' => $request->code
        ));

    echo json_encode(["status" => true, "msg" => "Offer updated"]);
} else {
    echo json_encode(["status" => false, "msg" => "Method not allowed"]);
}
