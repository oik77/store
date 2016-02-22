<?php
function validateText($data) {
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}

function includeListItems($limit, $offset, $orderBy, $desc) {
    require RESOURCES . "/config.php";

    $conn = mysqli_connect($serverName, $userName, $password, $schema);

    if (!$conn) {
        http_response_code(500);
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($orderBy === "cost" and $desc) {
        $query = "SELECT * FROM products ORDER BY cost DESC LIMIT ? OFFSET ?";
    } elseif ($orderBy === "cost") {
        $query = "SELECT * FROM products ORDER BY cost LIMIT ? OFFSET ?";
    } else {
        $query = "SELECT * FROM products LIMIT ? OFFSET ?";
    }

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
        die('statement execution failed' . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_bind_result($stmt,
        $productId, $name, $cost, $description, $imgUrl);

    while (mysqli_stmt_fetch($stmt)) {
        //params used in listItem as well as validateText
        include TEMPLATES . "listItem.php";
    }

    mysqli_stmt_close($stmt);

    mysqli_close($conn);
}
