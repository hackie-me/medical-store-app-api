<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/nms/vendor/autoload.php";
require "{$_SERVER['DOCUMENT_ROOT']}/nms/Lib/Fun.php";
header("Content-type: application/json");

use nms\Fun;
use Opis\Database\Database;
use Opis\Database\Connection;

// global variable 
const DB_DRIVER = 'mysql';
const DB_HOST = 'localhost';
const DB_USERNAME = 'root';
const DB_PASSWORD = '';
const DB_DATABASE = 'nms';
const DSN = DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_DATABASE;
const SECRET_KEY = 'nRM8jRhKCN0EZVs1uh3RRVgbnMSjOzfvenPxDp2cGhxqkMr45Evxf4SuDqGnxqSr';
const DOMAIN_NAME = 'www.nilkanth-medical-store.com';
define("STORAGE_PATH", "{$_SERVER['DOCUMENT_ROOT']}/nms/storage/");
$date   = new DateTimeImmutable();
$fun = new Fun();

try {
    $connection = Connection::fromPDO(new PDO(DSN, DB_USERNAME, DB_PASSWORD));
    $db = new Database($connection);
} catch (PDOException|Exception $ex) {
    echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
    http_response_code(500);
    die();
}
