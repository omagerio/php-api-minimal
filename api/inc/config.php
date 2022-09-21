<?php
ini_set("error_reporting", 9999999999);
ini_set("display_errors", 1);

$configs = new stdClass();

// Default configurations
$configs->db_host = "localhost";
$configs->db_user = "";
$configs->db_pass = "";
$configs->db_name = "";

// Environment configurations
if ($GLOBALS["ENV_NAME"] == "develop") {
    $configs->db_host = "localhost";
    $configs->db_user = "";
    $configs->db_pass = "";
    $configs->db_name = "";
}

if ($GLOBALS["ENV_NAME"] == "staging") {
    $configs->db_host = "localhost";
    $configs->db_user = "";
    $configs->db_pass = "";
    $configs->db_name = "";
}

if ($GLOBALS["ENV_NAME"] == "master") {
    $configs->db_host = "localhost";
    $configs->db_user = "";
    $configs->db_pass = "";
    $configs->db_name = "";
}