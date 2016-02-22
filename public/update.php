<?php
define('RESOURCES', dirname(__DIR__) . "/resources/");
define('TEMPLATES', RESOURCES . "templates/");

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    die("Method Not Allowed ");
}

$productId = filter_var($_GET["productId"], FILTER_VALIDATE_INT);
$name = $_GET["name"];
$cost = $_GET["cost"];
$description = $_GET["description"];
$imgUrl = $_GET["img_url"];

if ($productId === false) {
    http_response_code(400);
    die("Invalid productId");
}

if (!empty($cost) and filter_var($cost, FILTER_VALIDATE_FLOAT) === false) {
    http_response_code(400);
    die("Invalid cost");
}

$updateValues = array();

if (!empty($name)) {
    $updateValues[] = "name='" . $name . "'";
}
if (!empty($cost)) {
    $updateValues[] = "cost=" . $cost;
}
if (!empty($description)) {
    $updateValues[] = "description='" . $description . "'";
}
if (!empty($imgUrl)) {
    $updateValues[] = "img_url='" . $imgUrl . "'";
}

$updateValuesStr = implode(",", $updateValues);

if (empty($updateValuesStr)) {
    mysqli_close($conn);
    die("nothing updated");
}

require_once(RESOURCES . "/config.php");

$conn = mysqli_connect($serverName, $userName, $password, $schema);

if (!$conn) {
    http_response_code(500);
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "UPDATE products SET " . $updateValuesStr . " WHERE id_products=?";
$stmt = mysqli_prepare($conn, $sql);

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
    die('statement execution failed' . mysqli_stmt_error($stmt));
}

mysqli_stmt_close($stmt);

mysqli_close($conn);
