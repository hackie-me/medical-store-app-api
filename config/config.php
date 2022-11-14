<?php
require_once '../vendor/autoload.php';
header("Content-type: application/json");

// including namespaces 
use Opis\Database\Database;
use Opis\Database\Connection;

// global variable 
define("DB_DRIVER", 'mysql');
define("DB_HOST", 'localhost');
define("DB_USERNAME", 'root1');
define("DB_PASSWORD", '');
define("DB_DATABASE", 'nms');
define("SECRET_KEY", 'nRM8jRhKCN0EZVs1uh3RRVgbnMSjOzfvenPxDp2cGhxqkMr45Evxf4SuDqGnxqSr');
define("DOMAIN_NAME", 'www.nilkanth-medical-store.com');

try {
    // New connection of database 
    $connection = new Connection(
        DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_DATABASE,
        DB_USERNAME,
        DB_PASSWORD
    );

    // Connecting with database 
    define('DB', new Database($connection));
} catch (PDOException $ex) {
    echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
    die();
} catch (Exception $ex) {
    echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
    die();
}
