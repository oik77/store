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

function clearCache() {
    $memcache = memcache_connect('127.0.0.1', 11211);
    if ($memcache) {
        memcache_flush($memcache);
        memcache_close($memcache);
    } else {
        error_log("could not connect to memcached");
    }
}

function updateCache($productId, $name, $cost, $description, $imgUrl) {
    $memcache = memcache_connect('127.0.0.1', 11211);
    if ($memcache) {
        $itemKey = sprintf("store.item-%d", $productId);
        #error_log("update cache start key: " . $itemKey);
        $listKeys = memcache_get($memcache, $itemKey);
        if ($listKeys === false) return;

        foreach ($listKeys as $listKey => $value) {
            #error_log("update cache " . $listKey);
            $list = memcache_get($memcache, $listKey);

            if (!$list) continue;

            foreach ($list as $num => $item) {
                if ($item["id_products"] === $productId) break;
            }
            $list[$num]["name"] = $name;
            $list[$num]["cost"] = $cost;
            $list[$num]["description"] = $description;
            $list[$num]["img_url"] = $imgUrl;

            memcache_replace($memcache, $listKey, $list, 0, 3600);
        }

        memcache_close($memcache);
    } else {
        error_log("could not connect to memcached");
    }
}
