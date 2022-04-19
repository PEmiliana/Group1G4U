<?php
include "../Pages/databaseConnection.php";
$slot = $_GET['slot'];
$userID = $_GET['staffID'];
$orderID = $_GET['orderID'];
$status = $_GET['status'];

if (isset($slot) && isset($userID) && isset($orderID)) {

    $col1 = "authoriserStaffID".$slot;
    $col2 = "authoriser" .$slot."AuthTime";
    $col3 = "authoriser".$slot."Status";

    $date = date('Y-m-d H:i:s');
    $query = "UPDATE `order` SET `$col1`='$userID',`$col2`='$date',`$col3`='$status' WHERE `orderID` = $orderID";

    $stmt = $pdo->prepare($query);
    $stmt->execute();

    
}
