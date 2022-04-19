<?php
include "../Functions/console_log.php";
include "../Pages/databaseConnection.php";
include "../Functions/checkLogin.php";
include "../Functions/redirect.php";
session_start();
if (isset($_POST['submit_row'])) {
    $user_data = check_login($pdo);
    $product = $_POST['product']; // Do this for the rest of the stuff tomorrow
    $subtotal = $_POST['subtotal'];
    $quantity = $_POST['quantity'];
    $supplierProductID = $_POST['supplierProductID'];




    $staffID = $user_data['staffID'];
    $date = date('Y-m-d H:i:s');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $createOrderSQL = "INSERT INTO `order` (`staffID`,`orderDate`,`state`)
    VALUES ('$staffID', '$date', 'Pending')";
    $pdo->exec($createOrderSQL);
    $orderID = $pdo->lastInsertId();
    for ($i = 0; $i < count($product); $i++) {
        $productID = $product[$i];
        $subtotalProductOrder = $subtotal[$i];
        $quantityProductOrder = $quantity[$i];
        $ordersSupplierProductID = $supplierProductID[$i];

        $createProductOrderSQL =
            "INSERT INTO `productorder` (`orderID`,`productID`,`quantity`,`priceOnPurchase`,`supplierProductID`)
            VALUES ('$orderID', '$productID', '$quantityProductOrder','$subtotalProductOrder','$ordersSupplierProductID')";
        $pdo->exec($createProductOrderSQL);
    }
    redirect("../Pages/Orders.php");
}
