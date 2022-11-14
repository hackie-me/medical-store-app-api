<?php
header("Content-type: application/json");

// global variabls 
$host = "localhost";
$username = "root";
$passsword = "";
$database = "nms";
$pdo = null;
$secret_Key = null;
try {
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$database", $username, $passsword);
    // echo json_encode(["success" => true]);
    $secret_Key = 'myKey';
    $domainName = "www.nilkanth-medical-store.com";

} catch (Exception $ex) {
    echo json_encode(["success" => false, "msg" => $ex->getMessage()]);
}
