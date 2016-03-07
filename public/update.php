<?php
define('RESOURCES', dirname(__DIR__) . "/resources/");
define('TEMPLATES', RESOURCES . "templates/");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    die("Method Not Allowed ");
}

require_once TEMPLATES . "memcacheTest.php";

memcacheTest(1);

$productId = filter_var($_POST["productId"], FILTER_VALIDATE_INT);
$name = $_POST["name"];
$cost = filter_var($_POST["cost"], FILTER_VALIDATE_FLOAT);
$description = $_POST["description"];
$imgUrl = $_POST["imgUrl"];

if ($productId === false) {
    http_response_code(400);
    die("Invalid productId: " . $_POST["productId"]);
}
if (empty($name)) {
    http_response_code(400);
    die("Product Name required");
}
if ($cost === false) {
    http_response_code(400);
    die("Invalid cost");
}
if (empty($description)) {
    $description = NULL;
}
if (empty($imgUrl)) {
    $imgUrl = NULL;
}


require_once RESOURCES . "/config.php";

$conn = mysqli_connect($serverName, $userName, $password, $schema);

if (!$conn) {
    http_response_code(500);
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "UPDATE products SET "
    . "name=?,"
    . "cost=?,"
    . "description=?,"
    . "img_url=? "
    . "WHERE id_products=?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    http_response_code(500);
    mysqli_close($conn);
    die("statement prepare error");
}

mysqli_stmt_bind_param($stmt, "sdssi",
    $name, $cost, $description, $imgUrl, $productId);

$success = mysqli_stmt_execute($stmt);

if (!$success) {
    http_response_code(500);
    mysqli_close($conn);
    die('statement execution failed: ' . mysqli_stmt_error($stmt));
}

mysqli_stmt_close($stmt);

mysqli_close($conn);

updateCache($productId, $name, $cost, $description, $imgUrl);
