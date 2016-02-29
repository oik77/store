<?php
define('RESOURCES', dirname(__DIR__) . "/resources/");
define('TEMPLATES', RESOURCES . "templates/");

require_once TEMPLATES . "memcacheTest.php";

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    die("Method Not Allowed ");
}

$desc = filter_var($_GET['desc'], FILTER_VALIDATE_BOOLEAN);
if ($_GET['orderBy'] === 'cost') {
    $orderBy = "cost";
} else {
    $orderBy = "";
}

if ($desc or empty($orderBy)) {
    $sortRef = "index.php?orderBy=cost";
} else {
    $sortRef = "index.php?orderBy=cost&desc=true";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Store</title>
    <link rel="stylesheet" href="css/store.css">
    <script src="//code.jquery.com/jquery-2.2.0.min.js"></script>
    <script src="js/store.js"></script>
</head>
<body>

<div id="toolbar">
    <button id="create-btn" type="button">Create</button>
    <button type="button" onclick="window.location.href='<?php echo $sortRef; ?>'">
    Sort by Cost
    </button>
</div>

<?php
require_once TEMPLATES . 'createForm.php';
require_once TEMPLATES . 'updateForm.php';
require_once TEMPLATES . 'productList.php';
?>

<div id="product-list">
    <?php includeListItems(100, 0, $orderBy, $desc); ?>
</div>

<button id="next-btn" data-order-by="<?php echo $orderBy; ?>" data-desc="<?php echo $desc; ?>" type="button">
    Next 100
</button>

</body>
</html>