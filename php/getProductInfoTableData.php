<?php
include "../Pages/databaseConnection.php";


$arrayOfData = array();
$arrayOfMonthNames = array();
$productID = $_GET['productID'];
for ($i = 0; $i < 12; $i++) {
    $value = 0;
    $month = date("m", strtotime("-" . $i . " months"));
    $month = (int)$month;
    $dateObj = DateTime::createFromFormat('!m', $month);
    $monthName = $dateObj->format('F');
    $query = "SELECT sum(quantity) as `total`
    FROM `productorder` INNER JOIN `order`
    ON `productorder`.orderID = `order`.orderID
    WHERE productID='$productID' AND MONTH(orderDate) = $month GROUP BY productID";


    $stmt = $pdo->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $total = $row['total'];
        $value += $total;
    }
    array_push($arrayOfMonthNames, $monthName);
    array_push($arrayOfData, $value);
}


$data1 = "[$arrayOfData[11],$arrayOfData[10],$arrayOfData[9],$arrayOfData[8],$arrayOfData[7],$arrayOfData[6],$arrayOfData[5],$arrayOfData[4],$arrayOfData[3],$arrayOfData[2],$arrayOfData[1],$arrayOfData[0]]";
$data2 = "$arrayOfMonthNames[11]#$arrayOfMonthNames[10]#$arrayOfMonthNames[9]#$arrayOfMonthNames[8]#$arrayOfMonthNames[7]#$arrayOfMonthNames[6]#$arrayOfMonthNames[5]#$arrayOfMonthNames[4]#$arrayOfMonthNames[3]#$arrayOfMonthNames[2]#$arrayOfMonthNames[1]#$arrayOfMonthNames[0]";


echo $data1 . "#" . $data2;
