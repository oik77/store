<?php
define('RESOURCES', dirname(__DIR__) . "/resources/");
define('TEMPLATES', RESOURCES . "templates/");
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        #toolbar {
            padding-bottom: 5px;
        }
        #create-form {
            padding: 5px;
        }
        .list-item {
            border: 1px solid #D5D5D5;
            margin-bottom: -1px;
            padding-bottom: 5px;
        }
        .item-img {
            float: left;
            width: 200px;
        }
        .item-content {
            margin-left: 200px;
            margin-right: 200px;
        }
        .item-cost {
            width: 200px;
            float: right;
        }
    </style>
    <script src="//code.jquery.com/jquery-2.2.0.min.js"></script>
</head>
<body>

<?php
require_once TEMPLATES . 'toolbar.php';
require_once TEMPLATES . 'createForm.php';
require_once TEMPLATES . 'produtList.php';
?>

</body>
</html>