<?php
// Application Configuration
const DOMAIN_NAME = "www.nilkanth-medical-store.com"; // Set Domain Name

// Database Configuration
const DB_DRIVER = 'mysql';
const DB_HOST = 'localhost';
const DB_USERNAME = 'root';
const DB_PASSWORD = '';
const DB_DATABASE = 'nms';
const DSN = DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_DATABASE;

// JWT Configuration
const SECRET_KEY = "nRM8jRhKCN0EZVs1uh3RRVgbnMSjOzfvenPxDp2cGhxqkMr45Evxf4SuDqGnxqSr";

// Mail Configuration
CONST MAIL_USERNAME = "mail@nms.com";
CONST MAIL_PASSWORD = "";
CONST MAIL_HOST = "mail.nms.com";
CONST MAIL_PORT = 465;
CONST MAIL_ENCRYPTION = "SSL";
CONST MAIL_FROM = "mail@nms.com";
CONST MAIL_FROM_NAME = "Nilkanth Medical Store";
CONST MAIL_ALT_BODY = "This is the body in plain text for non-HTML mail clients";
CONST MAIL_IS_HTML = true;
CONST MAIL_IS_SMTP = true;

// PATH Configuration
define('STORAGE_PATH', "{$_SERVER['DOCUMENT_ROOT']}/nms/storage/");
define('ROOT_PATH', "{$_SERVER['DOCUMENT_ROOT']}/nms/");

// server url
const SERVER_URL = "http://192.168.1.2/nms/";
