<?php

// Define your handler function
function handleRequest($request, ApiResponse $response)
{
    if (isset($request->hello) && $request->hello == "world") {
        $response->results[] = "Hello World!";
    }

    return $response;
}

// Boot up the api service
require("inc/init.php");
