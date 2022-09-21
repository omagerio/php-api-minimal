<?php

ini_set("display_errors", 0);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Credentials: *");
header("Content-type: application/json; charset=utf8");

function custom_error_handler($a = null, $b = null, $c = null)
{
    $response = new ApiResponse();
    $response->errors[] = implode(",", [$a, $b, $c]);
    outputResponse($response);
}

set_error_handler("custom_error_handler", E_ALL);
set_exception_handler("custom_error_handler");

$response = new ApiResponse();

try {

    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (!isset($_GET["payload"])) {
            $response->errors[] = "missing_payload";
            $input = "{}";
        } else {
            $input = $_GET["payload"];
        }
    } else {
        $input = file_get_contents("php://input");
    }

    $request = json_decode($input);
    $jsonError = json_last_error();

    if ($jsonError) {
        throw new Exception("json_error_" . $jsonError);
    }

    if ($request == null) {
        $request = new stdClass();
    }

    $request->headers = getallheaders();
    $response = handleRequest($request, $response);
} catch (Exception $e) {
    $response->errors[] = $e->getMessage();
}

outputResponse($response);
