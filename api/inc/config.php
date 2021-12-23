<?php
$configs = new stdClass();

// Default configurations
$configs->connectionString = "mysql:host=localhost;dbname=textmo";
$configs->dbUser = "omar";
$configs->dbPassword = "omar";

// Environment configurations
if (ENV_NAME == "dev") {
    $configs->connectionString = "mysql:host=localhost;dbname=textmo";
    $configs->dbUser = "omar";
    $configs->dbPassword = "omar";
}
