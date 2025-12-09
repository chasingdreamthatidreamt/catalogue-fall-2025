<?php

session_start();

require_once __DIR__ . '/connect.php';
$connection = db_connect();

function authenticate($username, $password)
{
    global $connection;

    $statement = $connection->prepare(
        "SELECT `id`, `password_hash`
         FROM `catalogue_admin`
         WHERE `username` = ?;"
    );

    if (!$statement) {
        die("Prepare failed: " . $connection->error);
    }

    $statement->bind_param("s", $username);
    $statement->execute();
    $statement->store_result();

    if ($statement->num_rows > 0) {
        $statement->bind_result($id, $hashed_pass);
        $statement->fetch();

        if (password_verify($password, $hashed_pass)) {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['last_regeneration'] = time();

            return true;
        }
    }

    return false;
}

function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

function require_login()
{
    if (!is_logged_in()) {
        header("Location: login.php");
        exit();
    }
}

function logout()
{
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}