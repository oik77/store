<?php

function memcacheTest($weight, $timeout = 1) {
    $memcache = memcache_connect("127.0.0.1", 11211);
    $maxWeight = 17; # 17 requests per second ~ 1000 requests per minute
    $CONN_NUM_KEY = "store.connection-number";

    if ($memcache) {
        $currentNumber = memcache_get($memcache, $CONN_NUM_KEY);
        #error_log(sprintf("memcache test: %d connections", $currentNumber));
        if ($currentNumber === false) {
            memcache_set($memcache, $CONN_NUM_KEY, $weight, 0, $timeout);
        } elseif ($currentNumber > $maxWeight) {
            http_response_code(202);
            die('server is busy');
        } else {
            memcache_increment($memcache, $CONN_NUM_KEY, $weight);
        }

        memcache_close($memcache);
    } else {
        error_log("could not connect to memcached");
    }
}