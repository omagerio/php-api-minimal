<?php

function md5s($string, $salt = "_salt"){
    return md5($string . $salt);
}

function uuid($length = 8){
    $chars = "qwertyuiopasdfghjklzxcvbnm";
    $out = "";
    while(strlen($out) < $length){
        $out .= $chars[rand(0, strlen($chars)-1)];
    }
    return $out;
}

function get_base64_contents($base64){
    $parts = explode(";", $base64);
    $mime_type = explode(":", $parts[0])[1];
    $content = explode(",", $parts[1]);
    return [$mime_type, base64_decode($content[1])];
}

function create_filename_from_mime($mime){
    $filename = uniqid();

    if(strpos($mime, "jpg") !== false || strpos($mime, "jpeg") !== false){
        $filename .= ".jpg";
    }

    if(strpos($mime, "png") !== false){
        $filename .= ".png";
    }

    return $filename;
}