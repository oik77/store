<?php

function memcacheTest($weight, $timeout = 1) {
    $memcache = memcache_connect("127.0.0.1", 11211);
    $maxWeight = 17; # 17 requests per second ~ 1000 requests per minute

    if ($memcache) {
        $currentNumber = memcache_get($memcache, "store-connection");

        if ($currentNumber === false) {
            memcache_set($memcache, "store-connection", $weight, 0, $timeout);
        } elseif ($currentNumber > $maxWeight) {
            http_response_code(202);
            die('server is busy');
        } else {
            memcache_set($memcache, "store-connection", $currentNumber + $weight, 0, $timeout);
        }

        memcache_close($memcache);
    } else {
        error_log("could not connect to memcached");
    }
}