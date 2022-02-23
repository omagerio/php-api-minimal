<?php

if (isset($request->action)) {
    if ($request->action == "hello") {
        $response->results[] = "world";

        return $response;
    }
}