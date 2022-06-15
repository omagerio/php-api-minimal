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
            if ($include_response != null) {
                return $response;
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
