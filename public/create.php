<?php
$name = $cost = $description = $img_url = "";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(404);
    return;
}

if (empty($_POST["name"])) {
    http_response_code(400);
    echo "Product Name is required";
    return;
} else {
    $name = $_POST["name"];
}
if (empty($_POST["cost"])) {
    http_response_code(400);
    echo "Cost is required";
    return;
} else {
    $name = $_POST["name"];
}
if (!empty($_POST["description"])) {
    $description = $_POST["description"];
}
if (!empty($_POST["img_url"])) {
    $img_url = $_POST["img_url"];
}
