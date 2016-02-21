<div class="product-list">
<?php
require_once(RESOURCES . "/config.php");

$conn = mysqli_connect($serverName, $userName, $password, $schema);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM products LIMIT 20;";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $productID = $row["id_products"];
        $name = $row["name"];
        $cost = $row["cost"];
        $description = $row["description"];
        $imgUrl = $row["img_url"];
        include TEMPLATES . "listItem.php";
    }
} else {
    echo "0 results";
}

mysqli_close($conn);
?>
</div>