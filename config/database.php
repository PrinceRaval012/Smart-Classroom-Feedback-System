<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "smart_classroom_feedback";

// Create database connection
$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);

// Check database connection
if ($conn->connect_error) {

    error_log(
        "Database Connection Error: " .
        $conn->connect_error
    );

    die(
        "Sorry, we are unable to connect to the database. Please try again later."
    );
}

// Set character encoding
if (!$conn->set_charset("utf8mb4")) {

    error_log(
        "Database Charset Error: " .
        $conn->error
    );

    die(
        "Database configuration error. Please try again later."
    );
}

?>