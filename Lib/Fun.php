<?php
namespace nms {
// Including config file
require_once "{$_SERVER['DOCUMENT_ROOT']}/nms/config/config.php";

    use DateTimeImmutable;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    class Fun
    {
        // function to generate new token 
        public static function generate_token($result): string
        {
            $date   = new DateTimeImmutable();
            $request_data = [
                'iat'  => $date->getTimestamp(),
                'data' => $result
            ];
            return JWT::encode(
                $request_data,
                SECRET_KEY,
                'HS512'
            );
        }
        // function to decode token 
        public static function verify_token()
        {
            // Authenticating user  
            try {
                $request = apache_request_headers();
                if (isset($request['Authorization'])) {
                    $token = explode(" ", $request['Authorization']);
                    $token = $token[1];
                    $data = JWT::decode($token, new Key(SECRET_KEY, 'HS512'));
                    $data = (array)$data->data;
                    if (!empty($db)) {
                        $validate = $db->from('users')->where('userid', $data[0])->select()->count();
                        if($validate == 1){
                            return $data;
                        }else{
                            http_response_code(401);
                            exit();
                        }
                    }else{
                        http_response_code(500);
                        exit();
                    }
                } else {
                    echo json_encode(["status" => true, "msg" => "user_auth token missing"]);
                    http_response_code(401);
                    exit();
                }
            } catch (\Exception $ex) {
                echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
                http_response_code(401);
                die();
            }
        }

        public function upload_image($image): bool
        {
            $target_dir = STORAGE_PATH . "/image/admin_profile_pictures/";
            $target_file = $target_dir . basename($image["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            // Check if image file is an actual image or fake image
            $check = getimagesize($image["tmp_name"]);
            if (!$check) {
                $uploadOk = 0;
            }
            // Check if file already exists
            if (file_exists($target_file)) {
                $uploadOk = 0;
            }
            // Check file size
            if ($image["size"] > 500000) {
                $uploadOk = 0;
            }
            // Allow certain file formats
            if (
                $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif"
            ) {
                $uploadOk = 0;
            }
            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                return false;
            } else {
                if (move_uploaded_file($image["tmp_name"], $target_file)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
}
