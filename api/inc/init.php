<?php
ini_set("error_reporting", 9999999999);
ini_set("display_errors", 1);

require(__DIR__ . "/rb.php");
require(__DIR__ . "/config.php");

class ApiResponse
{
    public $results;
    public $errors;
    public $timestamp;

    public function __construct()
    {
        $this->results = array();
        $this->errors = array();
        $this->timestamp = time();
    }
}

R::setup($configs->connectionString, $configs->dbUser, $configs->dbPassword);
R::freeze(true); // we do not want this API to change the database structure, do we?

// $input = file_get_contents("php://input");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Credentials: *");
header("Content-type: application/json; charset=utf8");

$request = new stdClass();
$response = new ApiResponse();

$updatedResponse = handleRequest($request, $response);

echo json_encode($updatedResponse);
die();