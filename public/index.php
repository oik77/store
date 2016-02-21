<?php
define('RESOURCES', dirname(__DIR__) . "/resources/");
define('TEMPLATES', RESOURCES . "templates/");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/store.css">
    <script src="//code.jquery.com/jquery-2.2.0.min.js"></script>
    <script src="js/store.js"></script>
</head>
<body>

<?php
require_once TEMPLATES . 'toolbar.php';
require_once TEMPLATES . 'createForm.php';
require_once TEMPLATES . 'produtList.php';
?>

</body>
</html>