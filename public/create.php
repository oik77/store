<?php
define('RESOURCES', dirname(__DIR__) . "/resources/");
define('TEMPLATES', RESOURCES . "templates/");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    die("Method Not Allowed ");
}

$name = $_GET["name"];
$cost = filter_var($_GET["cost"], FILTER_VALIDATE_FLOAT);
$description = $_GET["description"];
$imgUrl = $_GET["img_url"];

if (empty($name)) {
    http_response_code(400);
    die("Product Name required");
}

if ($cost === false) {
    http_response_code(400);
    die("Invalid cost");
}

require_once(RESOURCES . "/config.php");

$conn = mysqli_connect($serverName, $userName, $password, $schema);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (empty($description) and empty($imgUrl)) {
    $stmt = mysqli_prepare($conn, "INSERT INTO products(name, cost) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "sd", $name, $cost);
} elseif (empty($description)) {
    $stmt = mysqli_prepare($conn, "INSERT INTO products(name, cost, img_url) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sds", $name, $cost, $imgUrl);
} elseif (empty($imgUrl)) {
    $stmt = mysqli_prepare($conn, "INSERT INTO products(name, cost, description) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sds", $name, $cost, $description);
} else {
    $stmt = mysqli_prepare($conn, "INSERT INTO products(name, cost, description, img_url) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sdss", $name, $cost, $description, $imgUrl);
}

if (!$stmt) {
    http_response_code(500);
    mysqli_close($conn);
    die("statement prepare error");
}

$success = mysqli_stmt_execute($stmt);

if (!$success) {
    http_response_code(500);
    mysqli_close($conn);
    die('statement execution failed' . mysqli_stmt_error($stmt));
}

mysqli_stmt_close($stmt);

mysqli_close($conn);
