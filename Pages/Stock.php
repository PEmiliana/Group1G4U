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


$query = "SELECT * FROM product LIMIT 0,10";
$queryCount = "SELECT COUNT(*) FROM product";
$countstmt = $pdo->prepare($queryCount);
$countstmt->execute();
$stmt = $pdo->prepare($query);

$stmt->execute();

$idValue = 1;
$total_count = $countstmt->fetchColumn();
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/Sidebar.css">
    <link rel="stylesheet" href="../css/Stock.css">
    <link rel="stylesheet" href="../css/Everything.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.0/font/bootstrap-icons.min.css" integrity="sha512-H4E1ASW8Ru1Npd1wQPB7JClskV8Nv1FG/bXK6TWMD+U9YMlR+VWUZp7SaIbBVBV/iRtefsIsv9dLSN6fdUI36w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Stock</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        var locked = false;
        $(document).ready(function() {
            windowOnScroll();
        });

        function windowOnScroll() {

            $(window).on("scroll", function(e) {
                console.log("test1");
                if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                    if ($(".post-item").length < $("#total_count").val()) {
                        var lastID = $(".post-item:last").attr("id");
                        getMoreData(lastID);

                    }
                }
            });
        }

        function getMoreData(lastID) {
            var numberToFilterBy = document.getElementById("filterNumber").value;
            if (locked == false) {
                locked = true;
                $(window).off("scroll");
                if (lastID == "" || lastID == null) {
                    console.log("Error lastID has not been correctly calculated");

                } else {
                    document.getElementById("productsLoading").className = "d-block";
                };
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {

                        document.getElementById("productsContainer").innerHTML += this.responseText;
                        document.getElementById("productsLoading").className = "d-none";
                        windowOnScroll();
                        locked = false;
                    }
                };
                xmlhttp.open("GET",
                    "../php/getMoreProducts.php?lastID=" + lastID + "&filterNumber=" + numberToFilterBy, true);
                xmlhttp.send();

            }
        }

        function fitlerResultsBySearch() {
            var search = document.getElementById("searchInput").value;
            search = search.toUpperCase();
            const nodeList = document.querySelectorAll('.post-item');
            if (search == "" || search == null) {
                for (let i = 0; i < nodeList.length; i++) {
                    nodeList[i].className = "row post-item";
                }
            } else {
                for (let i = 0; i < nodeList.length; i++) {
                    var productName = nodeList[i].getElementsByTagName("a")[0].innerText;
                    productName = productName.toUpperCase();
   
                    if (productName.includes(search)) {
                        console.log(productName+"true");
                        nodeList[i].className = "row post-item";
                    }
                    else
                    {
                        nodeList[i].className = "row post-item d-none";
                    }
                }
            }


        }
    </script>
</head>

<body>
    <?php include "navbar.php" ?>
    <div id="main">
        <input type="hidden" name="total_count" id="total_count" value="<?php echo $total_count; ?>" />
        <div class="container">
            <div class="row my-3">
                <div class="mt-3 col-4">

                    <label>Search:<input id="searchInput" type="search" class="form-control form-control-sm" placeholder="" aria-controls="example" onkeyup="fitlerResultsBySearch()"></label>

                </div>
                <div class="col-6"></div>
                <div class="mt-3 col-2">
                    <label>
                        Show results
                    </label>
                    <select name="example_length" id="filterNumber" aria-controls="example" class="form-select form-select-sm">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

            </div>

        </div>



        <div class="container-fluid" id="productBox">
            <div class="container productContainer" id="productsContainer">
                <?php
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    console_log($row['productName']);
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
                                <h1 class="availableStock">Available Stock:</h1>
                                <div id="productInfo"><?php echo $row['stock'] ?></div>
                            </div>
                        </div>
                        <hr class="break">
                    </div>
                <?php
                    $idValue++;
                }
                ?>

            </div>
            <div class="d-none" id="productsLoading">
                <div class="container-fluid text-center">
                    <div class="spinner-border" role="status">

                    </div>
                    <div class="fs-1">
                        Loading...
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
    document.getElementById("currentPage").innerHTML = "Stock";
    </script>
</body>