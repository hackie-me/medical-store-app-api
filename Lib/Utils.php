<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Opis\Database\Connection;
use Opis\Database\Database;
use Rakit\Validation\Validator;
use Spatie\ImageOptimizer\OptimizerChain;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class Utils
{
    // Member variables
    public static Database $db;
    public OptimizerChain $optimizerChain;
    public DateTimeImmutable $date;

    // Constructor
    public function __construct()
    {
        $this->optimizerChain = OptimizerChainFactory::create();
        try {
            $conn = new PDO(DSN, DB_USERNAME, DB_PASSWORD);
            $connection = Connection::fromPDO($conn);
            self::$db = new Database($connection);
        } catch (PDOException $ex) {
            http_response_code(500);
            echo json_encode($ex->getMessage());
            die();
        }
    }

    // Get Database Connection
    public function getDb(): Database
    {
        return self::$db;
    }

    // function to generate new token
    public static function generate_token($result): string
    {
        $date = new DateTimeImmutable();
        $request_data = [
            'iat' => $date->getTimestamp(),
            'data' => $result
        ];
        return JWT::encode(
            $request_data,
            SECRET_KEY,
            'HS512'
        );
    }

    // function to verify user & admin
    public static function verify_token($admin = false)
    {
        // Authenticating user
        try {
            $request = apache_request_headers();
            if (isset($request['Authorization'])) {
                $token = explode(' ', $request['Authorization']);
                $token = $token[1];
                $data = JWT::decode($token, new Key(SECRET_KEY, 'HS512'));
                $data = (array)$data->data;
                if ($admin) {
                    return Utils::authenticate_user("admin", "id", $data['id'], $data);
                }else{
                    return Utils::authenticate_user("user", "userid", $data['userid'], $data);
                }
            } else {
                http_response_code(401);
                exit();
            }
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode($ex->getMessage());
            exit();
        }
    }

    // Function to refresh token
    public static function refresh_token($table, $column, $id, $data)
    {
        $result = self::$db->from($table)
            ->where($column)->is($id)->select()
            ->first();
        if ($result) {
            if ($result['token'] == $data['token']) {
                return Utils::generate_token($result);
            } else {
                http_response_code(401);
                exit();
            }
        } else {
            http_response_code(401);
            exit();
        }
    }

    // Function to authenticate user
    public static function authenticate_user($table, $column, $value, $data)
    {
        if (!empty(self::$db)) {
            $validate = self::$db->from($table)->where($column)->is($value)->select()->count();
            if ($validate == 1) {
                return $data;
            } else {
                http_response_code(401);
                exit();
            }
        } else {
            http_response_code(500);
            exit();
        }
    }

    // Function to validate last record id
    public function validate_last_id($id): void
    {
        $validator = new Validator;
        $validation = $validator->make(['last_record_id' => $id], [
            'last_record_id' => 'required|min:1',
        ]);
        $validation->validate();
        if ($validation->fails()) {
            $errors = $validation->errors();
            echo json_encode($errors->firstOfAll());
            http_response_code(406);
            exit;
        }
    }
}
