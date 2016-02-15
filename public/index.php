<!DOCTYPE html>
<html>
<body>

<?php
require_once('../resources/config.php');

$conn = mysqli_connect($serverName, $userName, $password, $schema);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "id: " . $row["id_products"]
            . " - Name: " . $row["name"]
            . " - Cost: " . $row["cost"]
            . " - Description: " . $row["description"]
            . " - Img: " . $row["img_url"]
            . "<br>";
    }
} else {
    echo "0 results";
}

mysqli_close($conn);
?>

</body>
</html>