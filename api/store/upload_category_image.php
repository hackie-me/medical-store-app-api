<?php
require '../../config/config.php';
if (empty($fun) || empty($db)) {
    http_response_code(500);
    die('No function name provided!');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Authenticating user
    $user = $fun->verify_token(true);
    // check $_Files has image or not
    $fun->check_image();

    // validate Last id
    $fun->validate_last_id($_POST['last_record_id'] ? $_POST['last_record_id'] : null);

    // generate new image name
    $image_name = uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    // check directory exists or not
    if (!is_dir(STORAGE_PATH . "image/category")) {
        mkdir(STORAGE_PATH . "image/category", 0777, true);
    }
    $path = STORAGE_PATH . "image/category/" . $image_name;
    // upload image
    if (move_uploaded_file($_FILES['image']['tmp_name'], $path)) {
        // inserting records into database
        try {
            $result = $db->update('category')
                ->where('id')->is($_POST['last_record_id'])
                ->set(array(
                    'image' => $image_name
                ));
            http_response_code(201);
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode($ex->getMessage());
            die();
        }
    } else {
        http_response_code(500);
        die();
    }
}