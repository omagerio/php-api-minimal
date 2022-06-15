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
    $configs->db_host = "localhost";
    $configs->db_user = "omar";
    $configs->db_pass = "omar";
    $configs->db_name = "gmbpresse_app";
}