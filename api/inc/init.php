<?php

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