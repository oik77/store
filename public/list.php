<?php
define('RESOURCES', dirname(__DIR__) . "/resources/");
define('TEMPLATES', RESOURCES . "templates/");

require_once TEMPLATES . "memcacheTest.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    die("Method Not Allowed ");
}

$limit = filter_var($_GET["limit"], FILTER_VALIDATE_INT);
$offset = filter_var($_GET["offset"], FILTER_VALIDATE_INT);

if ($limit === false) {
    http_response_code(400);
    die("Invalid limit");
}
if ($offset === false) {
    http_response_code(400);
    die("Invalid offset");
}

$desc = filter_var($_GET['desc'], FILTER_VALIDATE_BOOLEAN);
if ($_GET['orderBy'] === 'cost') {
    $orderBy = "cost";
} else {
    $orderBy = "";
}

require_once TEMPLATES . 'productList.php';

includeListItems($limit, $offset, $orderBy, $desc);
