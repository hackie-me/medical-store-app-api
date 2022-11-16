<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/nms/vendor/autoload.php';
require $_SERVER['DOCUMENT_ROOT'] . '/nms/Lib/Fun.php';
header("Content-type: application/json");

use nms\Fun;
use Opis\Database\Database;
use Opis\Database\Connection;

// global variable 
define("DB_DRIVER", 'mysql');
define("DB_HOST", 'localhost');
define("DB_USERNAME", 'root');
define("DB_PASSWORD", '');
define("DB_DATABASE", 'nms');
define("DSN", DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_DATABASE);
define("SECRET_KEY", 'nRM8jRhKCN0EZVs1uh3RRVgbnMSjOzfvenPxDp2cGhxqkMr45Evxf4SuDqGnxqSr');
define("DOMAIN_NAME", 'www.nilkanth-medical-store.com');
$date   = new DateTimeImmutable();
$fun = new Fun();

try {
    $connection = Connection::fromPDO(new PDO(DSN, DB_USERNAME, DB_PASSWORD));
    $db = new Database($connection);
} catch (PDOException $ex) {
    echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
    die();
} catch (Exception $ex) {
    echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
    die();
}
