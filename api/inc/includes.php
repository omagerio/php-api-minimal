<?php

if (!file_exists(__DIR__ . "/../env")) {
    die("Missing env.php file in api folder");
}

$GLOBALS["ENV_NAME"] = trim(file_get_contents(__DIR__ . "/../env"));

require(__DIR__ . "/config.php");

$internals = scandir(__DIR__ . "/../internal");
foreach($internals as $internal){
    if (substr($internal, -4) == ".php") {
        require(__DIR__ . "/../internal/" . $internal);
    }
}

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

function handleRequest($request, ApiResponse $response = null)
{
    global $orm, $configs;
    if($response === null){
        $response = new ApiResponse();
    }

    if (isset($request->action)) {
        $handlersFiles = scandir(__DIR__ . "/../handlers");
        foreach ($handlersFiles as $handlerFile) {
            if (substr($handlerFile, -4) == ".php") {
                $include_response = require(__DIR__ . "/../handlers/" . $handlerFile);
                if (!is_numeric($include_response)) {
                    return $response;
                }
            }
        }
    }

    return $response;
}

function outputResponse(ApiResponse $response){
    echo json_encode($response);
    die();
}

require(__DIR__ . "/orm.php");

$orm = new DbOrm(
    $configs->db_host,
    $configs->db_user,
    $configs->db_pass,
    $configs->db_name
);
