<?php
ini_set("error_reporting", 9999999999);
ini_set("display_errors", 1);

if (!file_exists(__DIR__ . "/../env.php")) {
    die("Missing env.php file in api folder");
}

require(__DIR__ . "/../env.php");
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
        $this->timestamp = microtime(true);
    }
}

$apiHandlers = array();

function addApiHandler($handler)
{
    global $apiHandlers;
    $apiHandlers[] = $handler;
}

$handlersFiles = scandir(__DIR__ . "/../handlers");
foreach ($handlersFiles as $handlerFile) {
    if (substr($handlerFile, -4) == ".php") {
        include(__DIR__ . "/../handlers/" . $handlerFile);
    }
}

R::setup($configs->connectionString, $configs->dbUser, $configs->dbPassword);
R::freeze(true); // we do not want this API to change the database structure, do we?

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Credentials: *");
header("Content-type: application/json; charset=utf8");

$response = new ApiResponse();

try {

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $input = urldecode($_SERVER["QUERY_STRING"]);
    } else {
        $input = file_get_contents("php://input");
    }

    $request = json_decode($input);
    $jsonError = json_last_error();
    if ($jsonError) {
        throw new Exception("Json Error: " . $jsonError);
    }

    foreach ($apiHandlers as $apiHandler) {
        $response = call_user_func($apiHandler, $request, $response);
    }
} catch (Exception $e) {
    $response->errors[] = $e->getMessage();
}

echo json_encode($response);
die();
