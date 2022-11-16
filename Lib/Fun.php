<?php

namespace nms {

    use DateTimeImmutable;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    class Fun
    {
        // function to generate new token 
        public static function generate_token($result)
        {
            $date   = new DateTimeImmutable();
            $request_data = [
                'iat'  => $date->getTimestamp(),
                'data' => $result
            ];
            $token = JWT::encode(
                $request_data,
                SECRET_KEY,
                'HS512'
            );
            return $token;
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
                    $authUser = (array)$data->data;
                    return $authUser;
                } else {
                    echo json_encode(["status" => true, "msg" => "auth token missing"]);
                    exit();
                }
            } catch (\Exception $ex) {
                echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
                die();
            }
        }
    }
}
