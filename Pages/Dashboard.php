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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="../css/dataTables.bootstrap5.min.css" />
    <link rel="stylesheet" href="../css/Dashboard.css" />
    <link rel="stylesheet" href="../css/Sidebar.css">
    <link rel="stylesheet" href="../css/Everything.css">
    <title>Dashboard</title>


    <script src="../js/getTableData.js"></script>
    <script>
    </script>


</head>

<body onload='getTableData("../php/getdashboardTableData.php")'>
    <?php include "navbar.php" ?>
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
                    <div class="col-md-12">
                        <h4>Dashboard</h4>
                    </div>
                </div>


                <?php

                $boxesQuery = 'SELECT 
                (SELECT count(state) from `order` WHERE state="Approved") as `totalApproved`,
                (SELECT count(state) from `order` WHERE state="Declined") as `totalDeclined`,
                (SELECT sum(stock) from `product`) as `totalStock`,
                count(orderID) as `totalOrders` FROM `order`';
                $results = $pdo->prepare($boxesQuery);
                $results->execute();
                $statistics = $results->fetch(PDO::FETCH_ASSOC);

                $totalApproved = $statistics['totalApproved'];
                $totalDeclined = $statistics['totalDeclined'];
                $totalStock = $statistics['totalStock'];
                $totalOrders = $statistics['totalOrders'];
                ?>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="card bg-primary text-white h-100 text-center">
                            <div class="card-body py-5">

                                <div class="text-center">
                                    <div class="icon-circle mx-auto">
                                        <div class="bi bi-cart-fill fs-1">

                                        </div>
                                    </div>
                                </div>



                                <div class="fs-5 my-auto">
                                    <div class="fs-2 fw-bold text-white">
                                        <?php echo $totalOrders ?>
                                    </div>
                                    <div class="font-lightgrey">
                                        Total orders
                                    </div>

                                </div>


                            </div>

                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-warning text-dark h-100 text-center">
                            <div class="card-body py-5">

                                <div class="text-center">
                                    <div class="icon-circle mx-auto">
                                        <div class="bi bi-archive-fill fs-1">

                                        </div>
                                    </div>
                                </div>



                                <div class="fs-5 my-auto">
                                    <div class="fs-2 fw-bold text-white">
                                        <?php echo $totalStock ?>
                                    </div>
                                    <div class="font-lightgrey">
                                        Total stock
                                    </div>

                                </div>


                            </div>

                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-success text-white h-100 text-center">
                            <div class="card-body py-5">

                                <div class="text-center">
                                    <div class="icon-circle mx-auto">
                                        <div class="bi bi-cart-check-fill fs-1">

                                        </div>
                                    </div>
                                </div>



                                <div class="fs-5 my-auto">
                                    <div class="fs-2 fw-bold text-white">
                                        <?php echo $totalApproved ?>
                                    </div>
                                    <div class="font-lightgrey">
                                        Number of accepted orders
                                    </div>

                                </div>


                            </div>

                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card bg-danger text-white h-100 text-center">
                            <div class="card-body py-5">

                                <div class="text-center">
                                    <div class="icon-circle mx-auto">
                                        <div class="bi bi-cart-x-fill fs-1">

                                        </div>
                                    </div>
                                </div>



                                <div class="fs-5 my-auto">
                                    <div class="fs-2 fw-bold text-white">
                                        <?php echo $totalDeclined ?>
                                    </div>
                                    <div class="font-lightgrey">
                                        Number of declined Orders
                                    </div>

                                </div>


                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-12 w-100">
                        <div class="card h-100">
                            <div class="card-header">
                                <span class="me-4"><i class="bi bi-bar-chart-fill"></i></span>
                                Number of Orders per Month
                            </div>
                            <div class="card-body">
                                <div id="uniqueChartSpinner" class=" text-center">
                                    <div class="spinner-border">

                                    </div>
                                    <div class="text-center fs-2 fw-bold">Loading...</div>
                                </div>
                                <canvas class="chart" width="400" height="150"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <div class="NotificationBox">
                <div class="Notification">Notifications</div>
                <?php
                $query = "SELECT * FROM product";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                $notificationNumber = 0;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $productName = $row['productName'];
                    if ($row['stock'] <= $row['stockToReorderAt']) {

                ?>
                        <div class="alert">
                            <span class="closebutton">&times;</span>
                            <strong>Low Stock</strong> <?php echo $productName ?> is below the recommended stock. Make an order immediately!
                        </div>
                    <?php
                        $notificationNumber++;
                    } else if ($row['stock'] < ($row['stockToReorderAt'] + 200) && $row['stock'] > $row['stockToReorderAt']) {

                    ?>
                        <div class="alert warning">
                            <span class="closebutton">&times;</span>
                            <strong>Warning!</strong> <?php echo $row['productName'] ?> has low stock, consider making an order soon.
                        </div>
                <?php
                        $notificationNumber++;
                    }
                }
                ?>
                <?php
                if ($notificationNumber == 0) {
                ?>

                    <div class="alert info">
                        <span class="closebutton">&times;</span>
                        <strong>Info!</strong>You have no notifications.
                    </div>
                <?php
                }

                ?>
            </div>
        </main>
    </div>

    </div>
    </main>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.2/dist/chart.min.js"></script>
    <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap5.min.js"></script>
    <script src="../js/table.js"></script>
    <script>
        document.getElementById("currentPage").innerHTML = "Home";
    </script>
    <script>
        var close = document.getElementsByClassName("closebutton");
        var i;

        for (i = 0; i < close.length; i++) {
            close[i].onclick = function() {
                var div = this.parentElement;
                div.style.opacity = "0";
                setTimeout(function() {
                    div.style.display = "none";
                }, 600);
            }
        }
    </script>
</body>

</html>