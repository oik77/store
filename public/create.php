<?php
define('RESOURCES', dirname(__DIR__) . "/resources/");
define('TEMPLATES', RESOURCES . "templates/");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(404);
    die("incorrect method");
}

$name = $_POST["name"];
$cost = $_POST["cost"];
$description = $_POST["description"];
$img_url = $_POST["img_url"];

if (empty($name) or empty($cost)) {
    http_response_code(400);
    die("Product Name is required");
}

require_once(RESOURCES . "/config.php");

$conn = mysqli_connect($serverName, $userName, $password, $schema);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (empty($description) and empty($img_url)) {
    $stmt = mysqli_prepare($conn, "INSERT INTO products(name, cost) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "sd", $name, $cost);
} elseif (empty($description)) {
    $stmt = mysqli_prepare($conn, "INSERT INTO products(name, cost, img_url) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sds", $name, $cost, $img_url);
} elseif (empty($img_url)) {
    $stmt = mysqli_prepare($conn, "INSERT INTO products(name, cost, description) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sds", $name, $cost, $description);
} else {
    $stmt = mysqli_prepare($conn, "INSERT INTO products(name, cost, description, img_url) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sdss", $name, $cost, $description, $img_url);
}

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);

mysqli_close($conn);
