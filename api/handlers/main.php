<?php
addApiHandler(function ($request, ApiResponse $response) {


    if (isset($request->hello)) {
        $response->results[] = "Hello World";
    }
    return $response;



});
