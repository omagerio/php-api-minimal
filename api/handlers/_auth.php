<?php

$public_actions = [];

if (!in_array($request->action, $public_actions)) {
    if (1) {
        // authorized
    } else {
        // not authorized
        $response->errors[] = "not_authorized";
        return $response;
    }
}
