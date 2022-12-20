<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/nms/vendor/autoload.php";
require "{$_SERVER['DOCUMENT_ROOT']}/nms/Lib/Fun.php";
header("Content-type: application/json");

use nms\Fun;
use Opis\Database\Database;
use Opis\Database\Connection;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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
define("ROOT_PATH", "{$_SERVER['DOCUMENT_ROOT']}/nms/");

$date   = new DateTimeImmutable();
$fun = new Fun();
$mail = new PHPMailer(true);

try {
    $connection = Connection::fromPDO(new PDO(DSN, DB_USERNAME, DB_PASSWORD));
    $db = new Database($connection);

    // Mail Configuration
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'user@example.com';                     //SMTP username
    $mail->Password   = 'secret';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

} catch (PDOException|Exception $ex) {
    echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
    http_response_code(500);
    die();
}
