<?php
include "../Pages/databaseConnection.php";


if (!empty($_GET['supplierID'])) {
    $ordernumber = $_GET['rownumber'];
    $supID = $_GET['supplierID'];
    $productID = $_GET['productID'];
    $query = "SELECT price,deliveryTimeInWorkingDays FROM supplierproduct
    WHERE supplierProductID=? LIMIT 1";

    $getSupplierPrices = $pdo->prepare($query);
    $getSupplierPrices->execute([$supID]);
    $prep = $getSupplierPrices->fetch(PDO::FETCH_ASSOC);
    $price = $prep['price'];
    $deliv = $prep['deliveryTimeInWorkingDays'];
    $array = [$price,$deliv];


    $query2 = "SELECT minimumOrderAmount from `product` WHERE productID=?";
    $getMinVal = $pdo->prepare($query2);
    $getMinVal->execute([$productID]);
    $result = $getMinVal->fetch(PDO::FETCH_ASSOC);
    $min = $result['minimumOrderAmount'];
    echo "$price#$deliv#$min";
} else {
    echo "0#0#0";
}
