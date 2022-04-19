<?php
include "../Pages/databaseConnection.php";
include "../Functions/console_log.php";

$productID = $_GET['id'];
$deliveryOrder = $_GET['deliveryOrder'];
$priceOrder = $_GET['priceOrder'];
$supplierNameOrder = $_GET['supplierNameOrder'];
$countryOrder = $_GET['countryOrder'];

$orders = array($supplierNameOrder, $priceOrder, $deliveryOrder, $countryOrder);
$ordersCorrespondingColumnName = array("supplierName", "price", "deliveryTimeInWorkingDays", "country");

$ordersNotNone=array();
$ordersNotNoneCorrespondongColumn=array();

$numberOfElementsThatAreNotNone = 0;
for ($i = 0; $i < count($orders); $i++) {
    if ($orders[$i] !== "none") {
        array_push($ordersNotNone,$orders[$i]);
        array_push($ordersNotNoneCorrespondongColumn,$ordersCorrespondingColumnName[$i]);
    }
}

$query =
    "SELECT * FROM supplierproduct
    INNER JOIN supplier
    ON supplierproduct.supplierID = supplier.supplierID
    WHERE productID =?";

if (count($ordersNotNone)>0) {
    $query .= " ORDER BY ";
    for ($y = 0; $y < count($ordersNotNone); $y++) {
        if ($ordersNotNone[$y] !== "none") {
            $query .= $ordersNotNoneCorrespondongColumn[$y] . " " . $ordersNotNone[$y];
            if ($y+1 < count($ordersNotNone)) {
                $query .= ",";
                
            }
        }
    }
}

$organiseData = $pdo->prepare($query);
$organiseData->execute([$productID]);

while ($row = $organiseData->fetch()) {
    echo "<tr>";
    echo "<td>" . $row['supplierName'] . "</td>";
    echo "<td>" . $row['country'] . "</td>";
    echo "<td>£" . $row['price'] . "</td>";
    echo "<td>" . $row['deliveryTimeInWorkingDays'] . " days</td>";
    echo "</td>";
    echo "</tr>";
}
