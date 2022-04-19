<?php
include "databaseConnection.php";
include "../Functions/checkLogin.php";
include "../Functions/console_log.php";
include "../Functions/redirect.php";
session_start();

$user_data = check_login($pdo);
if (!isset($user_data)) {
    redirect("Index.php");
}
$numOrders = 1;
$disableButton = false;

$getproductID = null;
$getsupplierID = null;
if (isset($_GET['supplierID'])) {
    $getsupplierID = $_GET['supplierID'];
}
if (isset($_GET['productID'])) {
    $getproductID = $_GET['productID'];
}

?>



<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/Sidebar.css">
    <link rel="stylesheet" href="../css/CreateOrder.css">
    <link rel="stylesheet" href="../css/Everything.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.0/font/bootstrap-icons.min.css" integrity="sha512-H4E1ASW8Ru1Npd1wQPB7JClskV8Nv1FG/bXK6TWMD+U9YMlR+VWUZp7SaIbBVBV/iRtefsIsv9dLSN6fdUI36w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>Create Order</title>

    <script>
        function repeatableOrder(rownumber) {
            var innerhtml = `
                        <div class="orderBox row align-middle align-items-center">

                            <div id="order` + rownumber + `_toggleVisibility" class="toggleVisibility background w-100 h-100">
                            </div>
                            <div id="order` + rownumber + `_toggleVisibilitySpinner" class="toggleVisibility spinner-border justify-content-center"></div>



                            <div id="order` + rownumber + `_productImage" class="col-md-4 justify-content-center d-flex">
                                <img  src="https://image.shutterstock.com/image-vector/ui-image-placeholder-wireframes-apps-260nw-1037719204.jpg" class="pictureOfProduct img-fluid float-left" alt="productPicture">
                            </div>
                            <div id="formDetailsBox" class="col-md-8 align-middle align-items-center">
                                 <div class="row">
                                    <div class="col-md-4">Product</div>
                                    <div class="col-md-4">Price</div>
                                </div> 
                            <div id="singleForm" class="row">
                                    <div class="col-md-4 text-center ">
                                        <select name="product[]" id="order` + rownumber + `_product" onchange="getProductSuppliers(this.value,` + rownumber + `)" name="selection" class="formDetails w-100 text-center ">
                                            <?php
                                            $query = "SELECT * FROM product";
                                            $stmt = $pdo->prepare($query);
                                            $stmt->execute();
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            ?>
                                                <option value="<?php echo $row['productID'] ?>">
                                                    <?php echo $row['productName'] ?>
                                                </option>

                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div id="order` + rownumber + `_price" class="col-md-4 text-center">
                                        <h1 id="order` + rownumber + `_priceActual" class="formDetails">Price</h1>
                                    </div>

                                    <div class="col-md-4 text-center">
                                        <button onclick="add_row();" id="formDetails" type="button" class=" btn btn-lg btn-success w-100 ">
                                            <i class="bi bi-plus-circle-fill"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">Supplier</div>
                                    <div class="col-md-4">Quantity</div>
                                </div>
                                <div class="row">

                                    <div class="col-md-4 text-center">
                                        <select name="supplierProductID[]" onchange="getProductPrice(document.getElementById('order` + rownumber + `_product').value,'` + rownumber + `',document.getElementById('order` + rownumber + `_supplier').value);" id="order` + rownumber + `_supplier" name="selection" class="formDetails w-100 text-center">
                                            <option value="Supplier">Supplier</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 text-center">
                                    <input min="1" step="1" value="1" name="quantity[]" id="order` + rownumber + `_quantity" onchange="getSubTotalOnChange('` + rownumber + `',this.value)" onkeyup="getSubTotalOnChange('` + rownumber + `',this.value)" style="width:100%" type="number" class="formDetails" placeholder="Quantity" />
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <button onclick=delete_row(` + rownumber + `) id="formDetails" type="button" class="btn btn-lg btn-danger  w-100"><i class="bi bi-dash-circle-fill"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">Delivery time</div>
                                    <div class="col-md-4">Subtotal</div>
                                </div>
                                <div class="row">

                                    <div class="col-md-4 text-center" id="order` + rownumber + `_delivery">
                                        <h1 class="formDetails">Delivery time</h1>
                                    </div>
                                    <div class="col-md-4 text-center" id="order` + rownumber + `_subtotal">
                                        <h1 class="formDetails">£0.00</h1>
                                    </div>
                                    <div class="col-md-4 text-center">
                                    </div>
                                </div>

                            </div>
                        </div>`;
            return (innerhtml);
        }

        function add_row() {
            $rownumber = $("#order_table data-tr").length;
            $rownumber++;
            $("#order_table:last data-tr:last").after("<data-tr id='order" + $rownumber + "'></data-tr>")
            document.getElementById("order" + $rownumber).innerHTML = repeatableOrder($rownumber);
            <?php $numOrders++; ?>
            getProductSuppliers(document.getElementById("order" + $rownumber + "_product").value, $rownumber);
        }

        function delete_row(rownumber) {
            $('#order' + rownumber).remove();
            <?php $numOrders--; ?>
        }

        function getProductSuppliers(value, rownumber, get_supplierID = null) {
            supplierID = null;
            if (value == "") {
                document.getElementById("order" + rownumber + "_supplier").innerHTML = "";
                document.getElementById("order" + rownumber + "_price").innerHTML = "<h1 class='formDetails'>£0.00</h1>";
                document.getElementById("order" + rownumber + "_productImage").innerHTML = ' <img id="productImage" src="https://image.shutterstock.com/image-vector/ui-image-placeholder-wireframes-apps-260nw-1037719204.jpg" class="pictureOfProduct img-fluid float-left" alt="productPicture">';
                return;
            } else {
                toggleLoadingWheel(rownumber);

                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 & this.status == 200) {
                        document.getElementById("order" + rownumber + "_supplier").innerHTML = this.responseText;
                        var supplierProductID = supplierID = document.getElementById('order' + rownumber + '_supplier').value;
                        var imageDone = false;
                        getProductImage(value, rownumber, function(response) {
                            if (response) {
                                getProductPrice(value, rownumber, supplierProductID, function(resp) {
                                    if (resp) {
                                        toggleLoadingWheel(rownumber);
                                    }
                                });
                            }
                        });


                    }
                };
                xmlhttp.open("GET", "../php/getProductSuppliers.php?productID=" + value + "&rownumber=" + rownumber + "&supplierID=" + get_supplierID, true);
                xmlhttp.send();
            }
        }

        function getProductImage(value, rownumber, callback) {
            if (value == "") {
                document.getElementById("order" + rownumber + "_productImage").innerHTML = ' <img id="productImage" src="https://image.shutterstock.com/image-vector/ui-image-placeholder-wireframes-apps-260nw-1037719204.jpg" class="pictureOfProduct img-fluid float-left" alt="productPicture">';
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 & this.status == 200) {
                        document.getElementById("order" + rownumber + "_productImage").innerHTML = this.responseText;
                        if (callback) {
                            callback(true);
                        }

                    }
                };
                xmlhttp.open("GET", "../php/getProductImage.php?productID=" + value + "&rownumber=" + rownumber, true);
                xmlhttp.send();
            }
        }

        function getProductPrice(value, rownumber, supplierID, callback) {
            if (value == "") {
                document.getElementById("order" + rownumber + "_price").innerHTML = "<h1 class='formDetails'>£0.00</h1>";
                console.log("Nothing found");
                return;
            } else {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 & this.status == 200) {
                        const nums = this.responseText.split('#');
                        console.log(nums);
                        document.getElementById("order" + rownumber + "_quantity").setAttribute("min",nums[2]);
                        if(document.getElementById("order" + rownumber + "_quantity").value< nums[2])
                        {
                            document.getElementById("order" + rownumber + "_quantity").value=nums[2];
                        }
                        
                        document.getElementById("order" + rownumber + "_price").innerHTML = '<h1 id="order' + rownumber + '_priceActual" value="' + nums[0] + '" class="formDetails">£' + nums[0] + '</h1>';
                        document.getElementById("order" + rownumber + "_delivery").innerHTML = '<h1 id="order' + rownumber + '_deliveryTimeActual" value="' + nums[1] + '" class="formDetails">' + nums[1] + ' Days</h1>'
                        var quantity = document.getElementById("order" + rownumber + "_quantity").value;
                        
                        getSubTotalOnChange(rownumber, quantity);
                        if (callback) {
                            callback(true);
                        }

                    }
                };
                xmlhttp.open("GET", "../php/getProductPrice.php?productID=" + value + "&rownumber=" + rownumber + "&supplierID=" + supplierID, true);
                xmlhttp.send();
            }
        }

        function getSubTotalOnChange(rownumber, quantity) {
            if (!isNaN(quantity)) {
                var singlePrice = document.getElementById("order" + rownumber + "_priceActual").textContent;
                singlePrice = singlePrice.substring(1);
                var subTotal = singlePrice * quantity;
                subTotal = subTotal.toFixed(2);
                document.getElementById("order" + rownumber + "_subtotal").innerHTML = '<h1 class="formDetails">£' + subTotal + '</h1><input type="hidden" name="subtotal[]" id="order' + rownumber + '_subtotalInput" value="' + subTotal + '" />';
            } else {
                document.getElementById("order" + rownumber + "_subtotal").innerHTML = '<h1 class="formDetails">£0.00</h1><input type="hidden" name="subtotal[]" id="order' + rownumber + '_subtotalInput" value="0" />';
            }

        }

        window.addEventListener("load", function() {
            var startProdID = <?php
                                if ($getproductID == null) {
                                    echo 'document.getElementById("order1_product").value';
                                } else {
                                    echo "'" . $getproductID . "'";
                                } ?>;

            getProductSuppliers(startProdID, '1', <?php
                                                    if ($getsupplierID !== null) {
                                                        echo "'" . $getsupplierID . "'";
                                                    } ?>);
        }, false);


        function toggleLoadingWheel(rownumber) {
            var identifier = document.getElementById("order" + rownumber + "_toggleVisibility");
            var identifier2 = document.getElementById("order" + rownumber + "_toggleVisibilitySpinner");
            if (identifier.classList.contains("toggleVisibility") && identifier2.classList.contains("toggleVisibility")) {
                identifier.classList.remove("toggleVisibility");
                identifier2.classList.remove("toggleVisibility");
                <?php $disableButton = true; ?>
            } else {
                identifier.classList.add("toggleVisibility");
                identifier2.classList.add("toggleVisibility");
                <?php $disableButton = false; ?>
            }
        }
    </script>





</head>

<body>
    <?php include "navbar.php" ?>
    <div id="main">
        <div class="container-fluid" id="productBox">

            <form method="post" action="../php/insertOrder.php">
                <data-table id="order_table">
                    <data-tr id="order1">
                        <div class=" orderBox row align-middle align-items-center" style="position:relative">

                            <div id="order1_toggleVisibility" class="toggleVisibility background w-100 h-100">
                            </div>
                            <div id="order1_toggleVisibilitySpinner" class="toggleVisibility spinner-border justify-content-center"></div>

                            <div id="order1_productImage" class="col-md-4 justify-content-center d-flex">
                                <img id="productImage" src="https://image.shutterstock.com/image-vector/ui-image-placeholder-wireframes-apps-260nw-1037719204.jpg" class="pictureOfProduct img-fluid float-left" alt="productPicture">
                            </div>
                            <div id="formDetailsBox" class="col-md-8 align-middle align-items-center">
                                <div class="row">
                                    <div class="col-md-4">Product</div>
                                    <div class="col-md-4">Price</div>
                                </div>
                                <div id="singleForm" class="row">
                                    <div class="col-md-4 text-center ">
                                        <select name="product[]" id="order1_product" onchange="getProductSuppliers(this.value,'1');" name="selection" class="formDetails w-100 text-center ">
                                            <?php
                                            $query = "SELECT * FROM product";
                                            $stmt = $pdo->prepare($query);
                                            $stmt->execute();
                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                if ($row['productID'] == $getproductID) {
                                            ?>
                                                    <option value="<?php echo $row['productID'] ?>" selected>
                                                        <?php echo $row['productName'] ?>
                                                    </option>
                                                <?php  } else {
                                                ?>
                                                    <option value="<?php echo $row['productID'] ?>">
                                                        <?php echo $row['productName'] ?>
                                                    </option>

                                            <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4 text-center" id="order1_price">
                                        <h1 id="order1_priceActual" value="0" class="formDetails">£0.00</h1>
                                    </div>

                                    <div class="col-md-4 text-center">
                                        <button onclick="add_row();" id="formDetails" type="button" class=" btn btn-lg btn-success w-100 ">
                                            <i class="bi bi-plus-circle-fill"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">Supplier</div>
                                    <div class="col-md-4">Quantity</div>
                                </div>
                                <div class="row">

                                    <div class="col-md-4 text-center">
                                        <select name="supplierProductID[]" onchange="getProductPrice(document.getElementById('order1_product').value,'1',document.getElementById('order1_supplier').value);" id="order1_supplier" name="selection" class="formDetails w-100 text-center">
                                            <option value="Supplier">Supplier</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <input min="1" step="1" name="quantity[]" value="1" id="order1_quantity" onchange="getSubTotalOnChange('1',this.value)" onkeyup="getSubTotalOnChange('1',this.value)" style="width:100%" type="number" class="formDetails" placeholder="Quantity" />
                                    </div>
                                    <div class="col-md-4">

                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-4">Delivery time</div>
                                    <div class="col-md-4">Subtotal</div>
                                </div>
                                <div class="row">

                                    <div id="order1_delivery" class="col-md-4 text-center">
                                        <h1 class="formDetails">Delivery time</h1>
                                    </div>
                                    <div class="col-md-4 text-center" id="order1_subtotal">
                                        <h1 class="formDetails">£0.00</h1>
                                        <input type="hidden" name="subtotal[]" id="order1_subtotalInput" value="0.00" />
                                    </div>
                                    <div class="col-md-4 text-center">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </data-tr>
                </data-table>

                <div class="row">
                    <div class="col-4"></div>
                    <button id="createOrder" type="submit" name="submit_row" class="btn btn-lg btn-success text-center col-4">
                        Create order
                    </button>
                    <div class="col-4"></div>
                </div>
            </form>
        </div>
    </div>



    <script>
        document.getElementById("currentPage").innerHTML = "Create Order";
    </script>
</body>