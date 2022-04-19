<?php
include "databaseConnection.php";
include "../Functions/checkLogin.php";
include "../Functions/console_log.php";
include "../Functions/redirect.php";
session_start();
$user_data = check_login($pdo);
if (!isset($user_data)) {
    redirect("Index.php");
} elseif (!isset($_GET['orderID'])) {
    redirect("Orders.php");
}

$orderID = $_GET['orderID'];

$orderData =
    "SELECT * FROM `order`
WHERE orderID =?";
$data = $pdo->prepare($orderData);
$data->execute([$orderID]);
$data = $data->fetch(PDO::FETCH_ASSOC);

if(!empty($data['authoriserStaffID1'])){
    $authoriser1NameQuery =
    "SELECT * FROM `staff`
WHERE staffID=?";
$auth1Name = $pdo->prepare($authoriser1NameQuery);
$auth1Name->execute([$data['authoriserStaffID1']]);
$auth1Name = $auth1Name->fetch(PDO::FETCH_ASSOC);
}

if(!empty($data['authoriserStaffID2'])){
    $authoriser2NameQuery =
    "SELECT * FROM `staff`
WHERE staffID=?";
$auth2Name = $pdo->prepare($authoriser2NameQuery);
$auth2Name->execute([$data['authoriserStaffID2']]);
$auth2Name = $auth2Name->fetch(PDO::FETCH_ASSOC);
}

$arrayOfSubtotalPrices = [];
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/Sidebar.css">
    <link rel="stylesheet" href="../css/Everything.css">
    <link rel="stylesheet" href="../css/FormDetails.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.0/font/bootstrap-icons.min.css" integrity="sha512-H4E1ASW8Ru1Npd1wQPB7JClskV8Nv1FG/bXK6TWMD+U9YMlR+VWUZp7SaIbBVBV/iRtefsIsv9dLSN6fdUI36w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Form Details</title>


    <script>
        function changeAuthorisationStatus(userID, authSlot, orderID,status) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                console.log(userID, authSlot, orderID);
                if (this.readyState == 4 & this.status == 200) {
                    document.location.reload(true);
                }
            };
            xmlhttp.open("GET", "../php/changeAuthorisationStatus.php?staffID=" + userID + "&slot=" + authSlot + "&orderID=" + orderID+"&status="+status, true);
            xmlhttp.send();
        }
    </script>
</head>

<body>
    <?php include 'navbar.php' ?>
    <div id="main">
        <div class="container-fluid" id="productBox">

            <?php
            $query =
                "SELECT * FROM(((
                    `order`INNER JOIN productorder
                    ON `order`.orderID = productorder.orderID) 
                    INNER JOIN supplierproduct
                    ON `productorder`.`supplierProductID` = supplierproduct.supplierProductID)
                    INNER JOIN product
                    ON `supplierproduct`.`productID` = product.productID)
                    WHERE productorder.orderID =?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$orderID]);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($arrayOfSubtotalPrices, $row['priceOnPurchase']);
            ?>


                <div class="row">
                    <div class="col-4 d-flex justify-content-center">
                        <img id="productImage" src=<?php echo "'../images/product/" . $row['productID'] . "/" . $row['imageDirectory'] . "'" ?> class="img-fluid float-left" alt="productPicture">
                    </div>
                    <div id="formDetailsBox" class="col-8 align-middle align-items-center d-flex">

                        <div class="container-fluid">
                            <h1 class="formDetails"><?php echo $row['productName'] ?></h1>
                            <br>
                            <h1 class="formDetails"><?php echo "£" . $row['price'] ?></h1>
                        </div>
                        <div class="container-fluid">
                            <h1 class="formDetails"><?php echo $row['quantity'] ?></h1>
                            <br>
                            <h1 class="formDetails"><?php echo "£" . number_format($row['priceOnPurchase'] * 1.2, 2) ?></h1>
                        </div>
                        <div class="container-fluid">
                            <h1 class="formDetails"><?php echo "£" . $row['priceOnPurchase'] ?></h1>
                            <br>
                            <h1 class="formDetails">Placeholder</h1>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>
        <div id="totalPriceContainer" class="row container-fluid align-middle align-items-center d-flex">
            <div class="col-1">
            </div>
            <div id="fullTotal" class="col-4">
                <h1 class="formDetails">
                    <?php
                    $VATGrandTotal = 0;
                    for ($i = 0; $i < count($arrayOfSubtotalPrices); $i++) {
                        $VATGrandTotal += $arrayOfSubtotalPrices[$i] * 1.2;
                    }
                    echo "VAT: £" . number_format($VATGrandTotal, 2);
                    ?>
                </h1>
            </div>
            <div class="col-2">
            </div>
            <div id="fullTotal" class="col-4">
                <h1 class="formDetails">
                    <?php
                    $totalWithoutVAT = 0;
                    for ($i = 0; $i < count($arrayOfSubtotalPrices); $i++) {
                        $totalWithoutVAT += $arrayOfSubtotalPrices[$i];
                    }
                    echo "Without VAT: £" . number_format($totalWithoutVAT, 2);
                    ?>
                </h1>
            </div>
            <div class="col-1">
            </div>
        </div>
        <br>
        <div class="container-fluid formDetails">
            <div class="row">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-md-5">

                            <div class="container-fluid">
                                <div class="text-center authorization">
                                <?php
                                    if (!empty($data['authoriserStaffID1'])) {
                                        echo $data['authoriser1Status'] ." by ". $auth1Name['firstName']." ".$auth1Name['lastName'];
                                    } else {
                                        echo "Pending Authorisation";
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="container-fluid ">
                                <div class="row py-2">
                                    <?php
                                    if ((empty($data['authoriserStaffID1']) && $user_data['orderAuthPermission'] != 0 && $user_data['staffID'] !== $data['authoriserStaffID1'] && $data['authoriserStaffID2']!= $user_data['staffID'])) {
                                    ?>
                                        <div class="col-6 text-center">
                                            <button onclick="changeAuthorisationStatus('<?php echo $user_data['staffID'] ?>','1','<?php echo $orderID ?>','Approved');" type="button" class="btn btn-primary">Approve</button>
                                        </div>
                                        <div class="col-6 text-center">
                                            <button onclick="changeAuthorisationStatus('<?php echo $user_data['staffID'] ?>','1','<?php echo $orderID ?>','Denied');" type="button" class="btn btn-danger">Decline</button>
                                        </div>

                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php if (!empty($data['authoriser1AuthTime'])) { ?>
                                <div class="container-fluid text-center authorization">

                                    <time datetime="2022-02-09"><?php echo date_format(date_create($data['authoriser1AuthTime']),"d/m/Y") ?></time>

                                </div>
                            <?php } ?>
                        </div>

                        <div class="col-md-2"></div>


                        <div class="col-md-5">

                            <div class="container-fluid">
                                <div class="text-center authorization">
                                <?php
                                    if (!empty($data['authoriserStaffID2'])) {
                                        echo $data['authoriser2Status'] ." by ". $auth2Name['firstName']." ".$auth2Name['lastName'];
                                    } else {
                                        echo "Pending Authorisation";
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="container-fluid ">
                                <div class="row py-2">
                                    <?php
                                    if ((empty($data['authoriserStaffID2']) && $user_data['orderAuthPermission'] != 0 && $user_data['staffID'] !== $data['authoriserStaffID2'] && $data['authoriserStaffID1']!= $user_data['staffID'])) {
                                        ?>
                                        <div class="col-6 text-center">
                                            <button onclick="changeAuthorisationStatus('<?php echo $user_data['staffID'] ?>','2','<?php echo $orderID ?>','Approved');" type="button" class="btn btn-primary">Approve</button>
                                        </div>
                                        <div class="col-6 text-center">
                                            <button onclick="changeAuthorisationStatus('<?php echo $user_data['staffID'] ?>','2','<?php echo $orderID ?>','Denied');" type="button" class="btn btn-danger">Decline</button>
                                        </div>

                                    <?php
                                    }
                                    ?>

                                </div>
                            </div>
                            <?php if (!empty($data['authoriser2AuthTime'])) { ?>
                                <div class="container-fluid text-center authorization">

                                    <time datetime="2022-02-09"><?php echo date_format(date_create($data['authoriser2AuthTime']),"d/m/Y") ?></time>

                                </div>
                            <?php } ?>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    
</body>
<script>
    document.getElementById("currentPage").innerHTML = "Order Details";
</script>