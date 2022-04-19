<?php
include "../Pages/databaseConnection.php";

$productID = $_GET['productID'];
$ordernumber = $_GET['rownumber'];

$query = "SELECT * FROM product
WHERE productID =?";

$getProductImage = $pdo->prepare($query);
$getProductImage->execute([$productID]);
while ($row = $getProductImage->fetch(PDO::FETCH_ASSOC)) {
    $productImage = $row['imageDirectory'];
    $productID=$row['productID'];
    echo '<img id="productImage" src="../images/product/'.$productID . "/" . $productImage . '" class="img-fluid float-left" alt="productPicture">';
}
