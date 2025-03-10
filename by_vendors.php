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

if (!isset($_GET['vendors']) || !is_array($_GET['vendors'])) {
    echo json_encode(["error" => "Не переданы категории"]);
    exit;
}

$vendors = $_GET['vendors'];
$placeholders = implode(',', array_fill(0, count($vendors), '?'));

$sql = "SELECT items.name, items.price, items.quantity, items.quality, vendors.v_name, category.c_name 
        FROM items 
        INNER JOIN vendors ON items.FID_Vendor = vendors.ID_Vendors 
        INNER JOIN category ON items.FID_Category = category.ID_Category
        where vendors.v_name IN ($placeholders)";

$stmt = $pdo->prepare($sql);
$stmt->execute($vendors);

$output_vendors = "<h2>Product sort by vendors:</h2>";
    $output_vendors .= "<table border='1'>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Quality</th>
                    <th>Vendor</th>
                    <th>Category</th>
                </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $output_vendors .= "<tr>";
        $output_vendors .= "<td>" . htmlspecialchars($row['name']) . "</td>";
        $output_vendors .= "<td>" . htmlspecialchars($row['price']) . "</td>";
        $output_vendors .= "<td>" . htmlspecialchars($row['quantity']) . "</td>";
        $output_vendors .= "<td>" . htmlspecialchars($row['quality']) . "</td>";
        $output_vendors .= "<td>" . htmlspecialchars($row['v_name']) . "</td>";
        $output_vendors .= "<td>" . htmlspecialchars($row['c_name']) . "</td>";
        $output_vendors .= "</tr>";
    }
    $output_vendors .= "</table>";
    echo $output_vendors;
?>