<?php

header("Content-Type: application/json");

$host = 'db';
$dbname = 'lb_pdo_goods';
$username = 'user';
$password = 'password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["error" => "Ошибка подключения: " . $e->getMessage()]);
    exit;
}

if (!isset($_GET['categories']) || !is_array($_GET['categories'])) {
    echo json_encode(["error" => "Не переданы категории"]);
    exit;
}

$categories = $_GET['categories'];
$placeholders = implode(',', array_fill(0, count($categories), '?'));

$sql = "SELECT items.name, items.price, items.quantity, items.quality, vendors.v_name, category.c_name 
        FROM items 
        INNER JOIN vendors ON items.FID_Vendor = vendors.ID_Vendors 
        INNER JOIN category ON items.FID_Category = category.ID_Category
        WHERE category.c_name IN ($placeholders)";

$stmt = $pdo->prepare($sql);
$stmt->execute($categories);

$output_category = "<h2>Product sort by category:</h2>";
    $output_category .= "<table border='1'>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Quality</th>
                    <th>Vendor</th>
                    <th>Category</th>
                </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $output_category .= "<tr>";
        $output_category .= "<td>" . htmlspecialchars($row['name']) . "</td>";
        $output_category .= "<td>" . htmlspecialchars($row['price']) . "</td>";
        $output_category .= "<td>" . htmlspecialchars($row['quantity']) . "</td>";
        $output_category .= "<td>" . htmlspecialchars($row['quality']) . "</td>";
        $output_category .= "<td>" . htmlspecialchars($row['v_name']) . "</td>";
        $output_category .= "<td>" . htmlspecialchars($row['c_name']) . "</td>";
        $output_category .= "</tr>";
    }
    $output_category .= "</table>";
    echo $output_category;
?>