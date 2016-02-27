<?php
$memcache = memcache_connect("127.0.0.1", 11211);

if ($memcache) {
    $currentNumber = memcache_get($memcache, "store-connection");

    if ($currentNumber === false) {
        memcache_set($memcache, "store-connection", 0, 0, 1);
    } elseif ($currentNumber > 16) {
        http_response_code(202);
        die('server is busy');
    } else {
        memcache_set($memcache, "store-connection", $currentNumber + 1, 0, 1);
    }

    memcache_close($memcache);
} else {
    error_log("could not connect to memcached");
}