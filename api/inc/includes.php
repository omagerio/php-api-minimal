<?php

if (!file_exists(__DIR__ . "/../.env")) {
    die("Missing env.php file in api folder");
}

$GLOBALS["ENV_NAME"] = file_get_contents(__DIR__ . "/../.env");

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

function handleRequest($request)
{
    global $orm;
    $response = new ApiResponse();

    $handlersFiles = scandir(__DIR__ . "/../handlers");
    foreach ($handlersFiles as $handlerFile) {
        if (substr($handlerFile, -4) == ".php") {
            $include_response = require(__DIR__ . "/../handlers/" . $handlerFile);
            if($include_response != null){
                break;
            }
        }
    }

    return $response;
}

require(__DIR__ . "/orm.php");

$orm = new DbOrm(
    $configs->db_host,
    $configs->db_user,
    $configs->db_pass,
    $configs->db_name
);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Credentials: *");
header("Content-type: application/json; charset=utf8");

$response = new ApiResponse();

try {

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $input = $_GET["payload"];
    } else {
        $input = file_get_contents("php://input");
    }

    $request = json_decode($input);
    $jsonError = json_last_error();
    if ($jsonError) {
        throw new Exception("Json Error: " . $jsonError);
    }

    if($request == null){
        $request = new stdClass();
    }

    $request->headers = getallheaders();
    $response = handleRequest($request);
} catch (Exception $e) {
    $response->errors[] = $e->getMessage();
}

echo json_encode($response);
die();