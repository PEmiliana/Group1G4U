<?php
include "../Pages/databaseConnection.php";

$productID = $_GET['productID'];
$ordernumber = $_GET['rownumber'];
$supplierID=null;
if(!empty($_GET['supplierID'])){
    $supplierID = $_GET['supplierID'];
}

$query = "SELECT * FROM supplierproduct
INNER JOIN supplier
ON supplierproduct.supplierID = supplier.supplierID
WHERE productID =?";

$getSupplierNames = $pdo->prepare($query);
$getSupplierNames->execute([$productID]);
while ($row = $getSupplierNames->fetch(PDO::FETCH_ASSOC)) {
    $supplierName = $row['supplierName'];
    $supplierProductID = $row['supplierProductID'];
    if($row['supplierID'] == $supplierID){
        echo "<option value='$supplierProductID' selected >$supplierName</option>";
    }
    else{
        echo "<option value='$supplierProductID'>$supplierName</option>";
    }
}
