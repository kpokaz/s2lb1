<?php
$host = 'db';
$dbname = 'lb_pdo_goods';
$username = 'user';
$password = 'password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
    $min_price = isset($_GET['min_price']) ? (int) $_GET['min_price'] : 1000;
    $max_price = isset($_GET['max_price']) ? (int) $_GET['max_price'] : 3000;

    
    $stmt = $pdo->prepare("SELECT items.name, items.price, items.quantity, items.quality, vendors.v_name, category.c_name 
        FROM items 
        INNER JOIN vendors ON items.FID_Vendor = vendors.ID_Vendors 
        INNER JOIN category ON items.FID_Category = category.ID_Category
        WHERE price BETWEEN ? AND ?");
    $stmt->execute([$min_price, $max_price]);
    
    $output_price = "<h2>Product sort by price:</h2>";
    $output_price .= "<table border='1'>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Quality</th>
                    <th>Vendor</th>
                    <th>Category</th>
                </tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $output_price .= "<tr>";
        $output_price .= "<td>" . htmlspecialchars($row['name']) . "</td>";
        $output_price .= "<td>" . htmlspecialchars($row['price']) . "</td>";
        $output_price .= "<td>" . htmlspecialchars($row['quantity']) . "</td>";
        $output_price .= "<td>" . htmlspecialchars($row['quality']) . "</td>";
        $output_price .= "<td>" . htmlspecialchars($row['v_name']) . "</td>";
        $output_price .= "<td>" . htmlspecialchars($row['c_name']) . "</td>";
        $output_price .= "</tr>";
    }
    $output_price .= "</table>";
    echo $output_price;
} catch (PDOException $e) {
    echo json_encode(["error" => "Ошибка подключения: " . $e->getMessage()]);
}
?>