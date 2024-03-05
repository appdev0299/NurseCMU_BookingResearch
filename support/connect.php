<?php
// Database configuration
$host = 'localhost';
$username = 'edonation';
$password = 'edonate@FON';
$database = 'booking';

// Create a new mysqli instance
$mysqli = new mysqli($host, $username, $password, $database);

// Check for connection errors
if ($mysqli->connect_errno) {
    die("Failed to connect to MySQL: " . $mysqli->connect_error);
}

// Set character set to UTF-8
$mysqli->set_charset('utf8');

// Connection successful
// echo "Connected to MySQL database successfully!";
