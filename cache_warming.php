<?php
define('RESOURCES', __DIR__ . "/resources/");
define('TEMPLATES', RESOURCES . "templates/");

function loadFromDB($conn, $limit, $offset, $orderBy, $desc) {

    if ($orderBy === "cost" and $desc) {
        $query = "SELECT id_products FROM products ORDER BY cost DESC LIMIT ? OFFSET ?";
    } elseif ($orderBy === "cost") {
        $query = "SELECT id_products FROM products ORDER BY cost LIMIT ? OFFSET ?";
    } else {
        $query = "SELECT id_products FROM products LIMIT ? OFFSET ?";
    }

    # optimization hack
    $query = "SELECT t.* FROM (" . $query . ") AS q JOIN products AS t ON q.id_products = t.id_products";

    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);

    mysqli_stmt_execute($stmt);

    $res = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);

    return $rows;
}

function saveInCache($memcache, $listKey, $rows, $timeout) {
    memcache_set($memcache, $listKey, $rows, 0, $timeout);
    foreach ($rows as $row) {
        $itemKey = sprintf("store.item-%d", $row["id_products"]);

        $listKeys = memcache_get($memcache, $itemKey);
        if ($listKeys === false) {
            $listKeys = array();
        }
        $listKeys[$listKey] = true;

        memcache_set($memcache, $itemKey, $listKeys, 0, $timeout);
    }
}

function createList($conn, $memcache, $limit, $offset, $orderBy, $desc) {
    $listKey = sprintf("store.limit=%d&offset=%d&orderBy=%s&desc=%d",
        $limit, $offset, $orderBy, $desc);

    printf("%s...", $listKey);

    $rows = loadFromDB($conn, $limit, $offset, $orderBy, $desc);
    $num_rows = count($rows);
    printf("%d rows...", $num_rows);

    saveInCache($memcache, $listKey, $rows, 3600);
    printf("cached\n");
}

require_once RESOURCES . "/config.php";


$memcache = memcache_connect("127.0.0.1", 11211) or die("could not connect to memcached");
$conn = mysqli_connect($serverName, $userName, $password, $schema) or die("could not connect to mysql");

for ($i = 0; $i < 100; ++$i) {
    createList($conn, $memcache, 100, $i * 100, "", false);
    createList($conn, $memcache, 100, $i * 100, "cost", false);
    createList($conn, $memcache, 100, $i * 100, "cost", true);
}

mysqli_close($conn);
memcache_close($memcache);
