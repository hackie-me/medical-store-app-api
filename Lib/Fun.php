<?php
namespace nms {
require './../config/config.php';
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
    }
}
