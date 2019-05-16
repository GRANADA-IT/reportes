<?php

// Set the database access information as constants
define('DB_USER', 'sa');
define('DB_PASSWORD', 'Gra.2018');
define('DB_HOST', '192.168.0.47');
define('DB_NAME', 'GRANADA');
$connectionInfo = array( "Database"=>DB_NAME, "UID"=>DB_USER, "PWD"=>DB_PASSWORD, "CharacterSet" => "UTF-8");

$dbc = sqlsrv_connect(DB_HOST, $connectionInfo);

// Terminate script if unable to connect to Database
if ($dbc === false ) {  
    echo "<p class=\"error\">Could not connect to DataBase.</p>";
    // Debugging message and script termination
    die(print_r( sqlsrv_errors(), true));  
}