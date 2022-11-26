<?php

namespace nms {

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
                    return (array)$data->data;
                } else {
                    echo json_encode(["status" => true, "msg" => "auth token missing"]);
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
