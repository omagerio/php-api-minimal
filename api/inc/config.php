<?php
$configs = new stdClass();

// Default configurations
$configs->db_host = "localhost";
$configs->db_user = "user";
$configs->db_pass = "pass";
$configs->db_name = "db";

// Environment configurations
if (ENV_NAME == "dev") {
    $configs->db_host = "localhost";
    $configs->db_user = "omar";
    $configs->db_pass = "omar";
    $configs->db_name = "gmbpresse_app";
}