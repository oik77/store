<?php
define('RESOURCES', dirname(__DIR__) . "/resources/");
define('TEMPLATES', RESOURCES . "templates/");
?>

<!DOCTYPE html>
<html>
<head>
    <script src="//code.jquery.com/jquery-2.2.0.min.js"></script>
</head>
<body>

<?php
require_once TEMPLATES . 'createForm.php';

require_once TEMPLATES . 'table.php';
?>

</body>
</html>