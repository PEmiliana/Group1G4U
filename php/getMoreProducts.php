<?php
include "../Pages/databaseConnection.php";

$filterNumber = $_GET['filterNumber'];
$lastID = $_GET['lastID'];
$idValue = $lastID + 1;
$query = "SELECT * FROM product LIMIT " . $lastID . "," . $filterNumber;
$stmt = $pdo->prepare($query);

$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
?>

    <div class="row post-item" id="<?php echo $idValue ?>">


        <div class="col-4">
            <img id="productImage" src=<?php echo "'../images/product/" . $row['productID'] . "/" . $row['imageDirectory'] . "'" ?> class="img-fluid float-left" alt="productPicture">
        </div>
        <div class="col-8 align-middle align-items-center d-flex">

            <div class="container-fluid">
                <a id="productName" <?php echo "href='ProductInfo.php?id=" . $row['productID'] . "'";
                    ?> class="text-decoration-none"><?php echo $row['productName'] ?></a>
                <br>
                <h1 class="availableStock">
                    Available Stock:</h1>
                <h3 id="productInfo"><?php echo $row['stock'] ?></h3>
            </div>
        </div>
        <hr>
    </div>





<?php
    $idValue++;
}
