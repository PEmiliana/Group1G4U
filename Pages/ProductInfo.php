<?php
include "databaseConnection.php";
include "../Functions/redirect.php";
include "../Functions/checkLogin.php";
include "../Functions/console_log.php";
session_start();
$user_data = check_login($pdo);
if (isset($_GET['id']) && isset($user_data)) {
    $productID = $_GET['id'];
} else {
    redirect("Index.php");
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/Sidebar.css">
    <link rel="stylesheet" href="../css/Everything.css">
    <link rel="stylesheet" href="../css/ProductInfo.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="..css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="..css/Dashboard.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.0/font/bootstrap-icons.min.css" integrity="sha512-H4E1ASW8Ru1Npd1wQPB7JClskV8Nv1FG/bXK6TWMD+U9YMlR+VWUZp7SaIbBVBV/iRtefsIsv9dLSN6fdUI36w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Product Details</title>
    <script src="../js/getTableData.js"></script>
    <script>
        var locked = false;

        function organiseTableData(header) {
            if (locked == false) {
                locked = true;
                if (header == "") {
                    documet.getElementById("tableData").innerHTML = "";
                    return;

                } else {
                    document.getElementById("tableData").className = "d-none";
                    document.getElementById("tableDataLoading").className = "d-block";
                    // This will change the icon for the selected header only
                    if (document.getElementById(header + "-sort").classList.contains("bi-caret-down-fill")) {
                        document.getElementById(header + "-sort").className = "bi bi-caret-up-fill";

                    } else if (document.getElementById(header + "-sort").classList.contains("bi-caret-up-fill")) {
                        document.getElementById(header + "-sort").className = "";

                    } else {
                        document.getElementById(header + "-sort").className = "bi bi-caret-down-fill"
                    }
                    // This will choose the sort order for all headers
                    var arrayOfTableHeaders = ['delivery', 'price', 'country', 'supplierName'];
                    var arrayOfOrders = [];
                    arrayOfTableHeaders.forEach(element => {
                        if (document.getElementById(element + "-sort").classList.contains("bi-caret-down-fill")) {
                            arrayOfOrders.push("ASC");
                        } else if (document.getElementById(element + "-sort").classList.contains("bi-caret-up-fill")) {
                            arrayOfOrders.push("DESC");
                        } else {
                            arrayOfOrders.push("none");
                        }
                    });
                    // All code after here will only happen once the other php file has finished its course
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {

                            document.getElementById("tableData").innerHTML = this.responseText;
                            document.getElementById("tableDataLoading").className = "d-none";
                            document.getElementById("tableData").className = "";
                            locked = false;
                        }
                    };
                    xmlhttp.open("GET",
                        "../php/orderProductInfoData.php?supplierNameOrder=" + arrayOfOrders[3] +
                        "&countryOrder=" + arrayOfOrders[2] +
                        "&priceOrder=" + arrayOfOrders[1] +
                        "&deliveryOrder=" + arrayOfOrders[0] +
                        "&id=" + <?php echo "'" . $productID . "'" ?>, true);
                    xmlhttp.send();
                }
            }

        }
    </script>

</head>

<body onload="getTableData('../php/getProductInfoTableData.php?productID=<?php echo $productID ?>')">
    <?php include "navbar.php" ?>
    <div id="main">
        <?php
        $query = "SELECT * FROM product WHERE productID=?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$productID]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <div class="container-fluid">
            <div class="row" id="productBox">
                <div class="col-4">
                    <img id="productImage img-thumbnail" src=<?php echo "'../images/product/" . $result['productID'] . "/" . $result['imageDirectory'] . "'" ?> class="productImage img-fluid float-left" alt="productPicture">
                </div>
                <div class="col-8 d-flex">
                    <div class="container-fluid">
                        <br>

                        <div class="d-flex row">
                            <h2 id="productInfo" class="  my-auto "><?php echo $result['productName'] ?></h2>

                        </div>
                        <br>
                        <div class="d-flex ">
                            <div id="productInfo"><?php echo $result['productDescription'] ?></div>
                        </div>



                    </div>
                </div>
            </div>


            <div class="table-responsive mt-3">
                <table class="table text-center table-striped ">
                    <thead>
                        <tr title="Click to filter" class="header table-primary">
                            <th class="stock-header" scope="col" id="supplierName-header" onclick="organiseTableData('supplierName')">
                                Supplier Name
                                <span id="supplierName-sort" class="">
                            </th>
                            <th class="stock-header" scope="col" id="country-header" onclick="organiseTableData('country')">
                                Supplier location <span id="country-sort" class="">
                            </th>
                            <th class="stock-header" scope="col" id="price-header" onclick="organiseTableData('price')">
                                Price<span id="price-sort" class="">
                            </th>
                            <th class="stock-header" scope="col" id="delivery-header" onclick="organiseTableData('delivery')">
                                Delivery time <span id="delivery-sort" class="">
                            </th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody class="" id="tableData">

                        <?php
                        $tablequery =
                            "SELECT * FROM supplierproduct
                         INNER JOIN supplier
                         ON supplierproduct.supplierID = supplier.supplierID
                         WHERE productID =?";
                        $stmt2 = $pdo->prepare($tablequery);
                        $stmt2->execute([$productID]);
                        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                        ?>

                            <tr>
                                <td><?php echo $row['supplierName'] ?></td>
                                <td><?php echo $row['country'] ?></td>
                                <td><?php echo "£" . $row['price'] ?></td>
                                <td><?php echo $row['deliveryTimeInWorkingDays'] ?> days</td>
                                <td><a href="CreateOrder.php?productID=<?php echo $row['productID'] ?>&supplierID=<?php echo $row['supplierID'] ?>" type="button" class="btn btn-primary w-100">Order item</a></td>

                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>

                <div class="d-none" id="tableDataLoading">
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

    </div>
    <div id="main">
        <div class="offcanvas offcanvas-start sidebar-nav bg-dark" tabindex="-1" id="sidebar">
            <div class="offcanvas-body p-0">
                <nav class="navbar-dark">
                    <ul class="navbar-nav">
                        <li>
                            <div class="text-muted small fw-bold text-uppercase px-3">
                                CORE
                            </div>
                        </li>
                        <li>
                            <a href="#" class="nav-link px-3 active">
                                <span class="me-2"><i class="bi bi-speedometer2"></i></span>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="my-4">
                            <hr class="dropdown-divider bg-light" />
                        </li>
                        <li>
                            <div class="text-muted small fw-bold text-uppercase px-3 mb-3">
                                Interface
                            </div>
                        </li>
                        <li>
                            <a class="nav-link px-3 sidebar-link" data-bs-toggle="collapse" href="#layouts">
                                <span class="me-2"><i class="bi bi-layout-split"></i></span>
                                <span>Layouts</span>
                                <span class="ms-auto">
                                    <span class="right-icon">
                                        <i class="bi bi-chevron-down"></i>
                                    </span>
                                </span>
                            </a>
                            <div class="collapse" id="layouts">
                                <ul class="navbar-nav ps-3">
                                    <li>
                                        <a href="#" class="nav-link px-3">
                                            <span class="me-2"><i class="bi bi-speedometer2"></i></span>
                                            <span>Dashboard</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="#" class="nav-link px-3">
                                <span class="me-2"><i class="bi bi-book-fill"></i></span>
                                <span>Pages</span>
                            </a>
                        </li>
                        <li class="my-4">
                            <hr class="dropdown-divider bg-light" />
                        </li>
                        <li>
                            <div class="text-muted small fw-bold text-uppercase px-3 mb-3">
                                Addons
                            </div>
                        </li>
                        <li>
                            <a href="#" class="nav-link px-3">
                                <span class="me-2"><i class="bi bi-graph-up"></i></span>
                                <span>Charts</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link px-3">
                                <span class="me-2"><i class="bi bi-table"></i></span>
                                <span>Tables</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
        <!-- offcanvas -->

        <main class="mt-5 pt-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 mb-12 w-100">
                        <div class="card h-100">
                            <div class="card-header">
                                <span class="me-4"><i class="bi bi-bar-chart-fill"></i></span>
                                Number of items sold per Month
                            </div>
                            <div class="card-body">
                                <div id="uniqueChartSpinner"  class=" text-center">
                                    <div class="spinner-border">

                                    </div>
                                    <div class="text-center fs-2 fw-bold">Loading...</div>
                                </div>
                                <canvas class="chart" width="400" height="150">

                                </canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
    <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap5.min.js"></script>
    <script src="../js/table.js"></script>

    <script>
        document.getElementById("currentPage").innerHTML = "Product Information";
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>


</html>