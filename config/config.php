<?php
header("Content-type: application/json");
require_once "{$_SERVER['DOCUMENT_ROOT']}/nms/vendor/autoload.php";
require_once "{$_SERVER['DOCUMENT_ROOT']}/nms/config/env.php";
require "{$_SERVER['DOCUMENT_ROOT']}/nms/Lib/Utils.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$date = new DateTimeImmutable();
$mail = new PHPMailer(true);
$fun = new Utils();
$db = $fun->getDb();

try {
    // Mail Configuration
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host       = MAIL_HOST;
    $mail->SMTPAuth   = MAIL_IS_SMTP;
    $mail->Username   = MAIL_USERNAME;
    $mail->Password   = MAIL_PASSWORD;
    $mail->SMTPSecure = MAIL_ENCRYPTION;
    $mail->Port       = MAIL_PORT;
    $mail->isHTML(MAIL_IS_HTML);
    $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
    $mail->AltBody = MAIL_ALT_BODY;
} catch (Exception $ex) {
    http_response_code(500);
    echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
    die();
}
