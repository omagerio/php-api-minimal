<?php
ini_set("error_reporting", 9999999999);
ini_set("display_errors", 1);

$configs = new stdClass();

// Default configurations
$configs->db_host = "localhost";
$configs->db_user = "user";
$configs->db_pass = "pass";
$configs->db_name = "db";

// Environment configurations
if ($GLOBALS["ENV_NAME"] == "dev") {
    $configs->db_host = "";
    $configs->db_user = "";
    $configs->db_pass = "";
    $configs->db_name = "";
}

if ($GLOBALS["ENV_NAME"] == "staging") {
    $configs->db_host = "";
    $configs->db_user = "";
    $configs->db_pass = "";
    $configs->db_name = "";
}