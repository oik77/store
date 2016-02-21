<div id="product-list">
<?php
require_once(RESOURCES . "/config.php");

function validateText($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

function includeListItem($product) {
    $productId = validateText($product["id_products"]);
    $name = validateText($product["name"]);
    $cost = validateText($product["cost"]);
    $description = validateText($product["description"]);
    $imgUrl = validateText($product["img_url"]);
    include TEMPLATES . "listItem.php";
}

$conn = mysqli_connect($serverName, $userName, $password, $schema);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM products LIMIT 100;";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        includeListItem($row);
    }
} else {
    echo "0 results";
}

mysqli_close($conn);
?>
</div>
