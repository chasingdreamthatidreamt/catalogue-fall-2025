<?php

function db_connect()
{

    $db_host = 'mysql';
    $db_user = 'student';
    $db_pass = 'student';
    $db_name = 'dmit2025';

    $connection = new mysqli($db_host, $db_user, $db_pass, $db_name);

    if ($connection->connect_errno) {
        die("Database connection failed: " . $connection->connect_error);
    }

    return $connection;
}

function db_disconnect($connection)
{
    if (isset($connection) && $connection instanceof mysqli) {
        $connection->close();
    }
}
