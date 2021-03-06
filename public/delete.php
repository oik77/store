<?php
define('RESOURCES', dirname(__DIR__) . "/resources/");
define('TEMPLATES', RESOURCES . "templates/");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    die("Method Not Allowed ");
}

require_once TEMPLATES . "memcacheTest.php";

memcacheTest(1);

$productId = filter_var($_GET["productId"], FILTER_VALIDATE_INT);
if ($productId === false) {
    http_response_code(400);
    die("Invalid productId");
}

require_once RESOURCES . "/config.php";

$conn = mysqli_connect($serverName, $userName, $password, $schema);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$stmt = mysqli_prepare($conn, "DELETE FROM products WHERE id_products=?");

if (!$stmt) {
    http_response_code(500);
    mysqli_close($conn);
    die("statement prepare error");
}

mysqli_stmt_bind_param($stmt, "i", $productId);

$success = mysqli_stmt_execute($stmt);

if (!$success) {
    http_response_code(500);
    mysqli_close($conn);
    die('statement execution failed: ' . mysqli_stmt_error($stmt));
}

mysqli_close($conn);

clearCache();
