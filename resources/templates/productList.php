<?php
function validateText($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

function loadFromDB($limit, $offset, $orderBy, $desc) {
    require RESOURCES . "/config.php";

    $conn = mysqli_connect($serverName, $userName, $password, $schema);

    if (!$conn) {
        http_response_code(500);
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($orderBy === "cost" and $desc) {
        $query = "SELECT id_products FROM products ORDER BY cost DESC LIMIT ? OFFSET ?";
    } elseif ($orderBy === "cost") {
        $query = "SELECT id_products FROM products ORDER BY cost LIMIT ? OFFSET ?";
    } else {
        $query = "SELECT id_products FROM products ORDER BY id_products LIMIT ? OFFSET ?";
    }

    # optimization hack
    $query = "SELECT t.* FROM (" . $query . ") AS q JOIN products AS t ON q.id_products = t.id_products";

    $stmt = mysqli_prepare($conn, $query);

    if (!$stmt) {
        http_response_code(500);
        mysqli_close($conn);
        die("statement prepare error");
    }

    mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);

    $success = mysqli_stmt_execute($stmt);

    if (!$success) {
        http_response_code(500);
        mysqli_close($conn);
        die('statement execution failed: ' . mysqli_stmt_error($stmt));
    }

    $res = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $rows;
}

function saveInCache($memcache, $listKey, $rows, $timeout) {
    memcache_set($memcache, $listKey, $rows, 0, $timeout);
    foreach ($rows as $row) {
        $itemKey = sprintf("store.item-%d", $row["id_products"]);
        # error_log('cache set:' . $itemKey);

        $listKeys = memcache_get($memcache, $itemKey);
        if ($listKeys === false) {
            $listKeys = array();
        }
        $listKeys[$listKey] = true;

        memcache_set($memcache, $itemKey, $listKeys, 0, $timeout);
    }
}

# desc: bool
function includeListItems($limit, $offset, $orderBy, $desc) {
    $listKey = sprintf("store.limit=%d&offset=%d&orderBy=%s&desc=%d",
        $limit, $offset, $orderBy, $desc);

    $memcache = memcache_connect("127.0.0.1", 11211);

    if ($memcache) {
        $rows = memcache_get($memcache, $listKey);
        if ($rows === false) {
            $rows = loadFromDB($limit, $offset, $orderBy, $desc);
            saveInCache($memcache, $listKey, $rows, 3600);
            $num_rows = count($rows);
            error_log(sprintf("cache set [key: %s, num_rows: %d]", $listKey, $num_rows));
        } else {
            $num_rows = count($rows);
            error_log(sprintf("cache hit [key: %s, num_rows: %d]", $listKey, $num_rows));
        }

        memcache_close($memcache);
    } else {
        error_log("could not connect to memcached");
        $rows = loadFromDB($limit, $offset, $orderBy, $desc);
    }

    foreach ($rows as $row) {
        $productId = $row['id_products'];
        $name = $row['name'];
        $cost = $row['cost'];
        $description = $row['description'];
        $imgUrl = $row['img_url'];
        include TEMPLATES . 'listItem.php';
    }
}
