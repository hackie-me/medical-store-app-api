<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Opis\Database\Connection;
use Opis\Database\Database;
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
                }
            } else {
                http_response_code(401);
                exit();
            }
        } catch (Exception $ex) {
            http_response_code(401);
            echo json_encode($ex->getMessage());
            exit();
        }
        return null;
    }

    // function to decode token

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
            echo json_encode('Database connection error');
            exit();
        }
    }


    // function to upload image and return image url
    public function upload_image($imageBase64, $path): string
    {
        // Split the string on the ";" character
        $parts = explode(';', str_replace('data:', '', $imageBase64));

        // Get the file extension
        $extension = explode('/', $parts[0])[1];

        // Generate a unique name for the image
        $imageName = uniqid() . '.' . $extension;
        $imagePath = $path. '/' . $imageName;
        $image = STORAGE_PATH . "image/$imagePath";
        try {
            // Save the image
            file_put_contents($image, file_get_contents($imageBase64));
            // Optimize the image
            $this->optimizerChain->optimize($image);
            // return image server url
            return SERVER_URL . 'storage/image/' . $imagePath;
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode($ex->getMessage());
            exit();
        }
    }

    // Function to Bulk upload image and return json array of image url
    public function bulk_upload_image($imageBase64, $path): string
    {
        $imageArray = [];
        foreach ($imageBase64 as $image) {
            $imageArray[] = $this->upload_image($image, $path);
        }
        return json_encode($imageArray);
    }

    // Function to delete image
    public function delete_media($imagePath): void
    {
        try {
            // Delete the image
            unlink($imagePath);
        } catch (Exception $ex) {
            http_response_code(500);
            echo json_encode($ex->getMessage());
            exit();
        }
    }

    // Function to delete bulk image
    public function bulk_delete_media($imagePath): void
    {
        $imageArray = json_decode($imagePath);
        foreach ($imageArray as $image) {
            $this->delete_media($image);
        }
    }
}
