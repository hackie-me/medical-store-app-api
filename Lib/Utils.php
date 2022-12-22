<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Opis\Database\Database;
use Opis\Database\Connection;
use Spatie\ImageOptimizer\OptimizerChain;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class Utils
{
    // Member variables
    public OptimizerChain $optimizerChain;
    public static Database $db;
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
            echo json_encode(['success' => false, 'msg' => $ex->getMessage()]);
            die();
        }
    }

    // getter for $db
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

    // function to decode token
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
                    if($admin){
                        return Utils::authenticate_user("admin", "id", $data['id'], $data);
                    }else{
                        return Utils::authenticate_user("user", "userid", $data['id'], $data);
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(['success' => false, 'msg' => 'Unauthorized']);
                    exit();
                }
            } catch (Exception $ex) {
                http_response_code(401);
                echo json_encode(['success' => false, 'msg' => $ex->getMessage()]);
                exit();
            }

    }

    // function to verify user & admin
    public static function authenticate_user($table, $column, $value, $data){
        if (!empty(self::$db)) {
            $validate = self::$db->from($table)->where($column)->is($value)->select()->count();
            if ($validate == 1) {
                return $data;
            } else {
                http_response_code(401);
                echo json_encode(['success' => false, 'msg' => 'Unauthorized']);
                exit();
            }
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'msg' => 'Database connection error']);
            exit();
        }
    }

    // function to upload image
    public function upload_image($image): bool
    {
        $target_dir = STORAGE_PATH . '/image/admin_profile_pictures/';
        $target_file = $target_dir . basename($image['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is an actual image or fake image
        $check = getimagesize($image['tmp_name']);
        if (!$check) {
            $uploadOk = 0;
        }
        // Check if file already exists
        if (file_exists($target_file)) {
            $uploadOk = 0;
        }
        // Check file size
        if ($image['size'] > 500000) {
            $uploadOk = 0;
        }
        // Allow certain file formats
        if (
            $imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg'
            && $imageFileType != 'gif'
        ) {
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            return false;
        } else {
            if (move_uploaded_file($image['tmp_name'], $target_file)) {
                return true;
            } else {
                return false;
            }
        }
    }

    // function to decode base64 image
    public function decode_image($base64String): string
    {
        $image_base64 = base64_decode($base64String);
        $image_name = uniqid() . '.png';
        $file = STORAGE_PATH . '/image/admin_profile_pictures/' . $image_name;
        // Saving image
        file_put_contents($file, $image_base64);

        // Compression of image
        $this->optimizerChain->optimize($file);
        return $image_name;
    }


}
