<?php
include "databaseConnection.php";
include "../Functions/checkLogin.php";
include "../Functions/console_log.php";
include "../Functions/redirect.php";
session_start();
$user_data = check_login($pdo);
if (!isset($user_data)) {
    redirect("Index.php");
} else if ($user_data['managingUserPermsAuthorisation'] == 0) {
    redirect("Index.php");
}


$numberOfRecords = 10;
if (!isset($_GET['page'])) {
    $pageNumber = 1;
    $offset = 0;
} else {
    $pageNumber = $_GET['page'];
    $offset = ($pageNumber - 1) * $numberOfRecords;
}

if (isset($_GET['numberOfRecords'])) {
    $numberOfRecords = $_GET['numberOfRecords'];
}


$query = "SELECT * FROM staff WHERE staffID != 'SYSTEM'";
$countQuery = "SELECT count(`staffID`) as 'total' FROM staff WHERE staffID != 'SYSTEM'";
if (!empty($_GET['navsearch'])) {
    $postvariable = $_GET['navsearch'];
    $query .= " AND `firstName` LIKE '%" . $postvariable . "%' OR `lastName` LIKE '%"
        . $postvariable . "%'";

    $countQuery .= " AND `firstName` LIKE '%" . $postvariable . "%' OR `lastName` LIKE '%"
        . $postvariable . "%'";
} else {
    $postvariable = "";
}

$query .= " LIMIT " . $offset . ", " . $numberOfRecords;
$countQuery .= " LIMIT " . $offset . ", " . $numberOfRecords;

$stmt = $pdo->prepare($query);
$stmt->execute();

$countQueryprep = $pdo->prepare($countQuery);
$countQueryprep->execute();
$prep = $countQueryprep->fetch(PDO::FETCH_ASSOC);
$count = $prep['total'];


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../Css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="../Css/Dashboard.css" />
    <link rel="stylesheet" href="../Css/Sidebar.css" />
    <link rel="stylesheet" href="../css/Everything.css">

    <title>Admin Dashboard</title>
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery-3.5.1.js"></script>

    <script>
        function Openform() {
            document.getElementById('form1').style.display = '';
        }
    </script>

    <script>
        function togglePopup(status, staffID = null) {
            if (staffID !== null) {
                document.getElementById("saveChangesToPermissions").onclick = function() {
                    updateUserPermissions(staffID);
                };
            }

            document.getElementById("updateComplete").className = "text-center d-none"
            document.getElementById("popup-1").classList.toggle("active");
            document.getElementById("staffModalData").className = "d-none";
            document.getElementById("loadingSpinnerModal").className = "align-middle";

            if (status == "opening" && staffID !== null) {
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 & this.status == 200) {
                        const nums = this.responseText.split('#');
                        var viewOrderPrivilege = nums[0];
                        var managePerms = nums[1];
                        var orderAuth = nums[2];
                        if (viewOrderPrivilege == 1) {
                            document.getElementById("viewOrderPrivilege").checked = true;
                        } else {
                            document.getElementById("viewOrderPrivilege").checked = false;

                        }
                        if (managePerms == 1) {
                            document.getElementById("manageUserPerms").checked = true;
                        } else {
                            document.getElementById("manageUserPerms").checked = false;
                        }
                        if (orderAuth == 1) {
                            document.getElementById("orderAuth").checked = true;
                        } else {
                            document.getElementById("orderAuth").checked = false;
                        }
                        document.getElementById("staffModalData").className = "";
                        document.getElementById("loadingSpinnerModal").className = "d-none";


                    }
                };
                xmlhttp.open("GET", "../php/getStaffPermissions.php?staffID=" + staffID, true);
                xmlhttp.send();
            }
        }

        var locked = false;

        function updateUserPermissions(staffID) {
            if (locked = false) {
                locked = true;
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 & this.status == 200) {
                        document.getElementById("updateComplete").className = "text-center";
                        locked = false;
                    }
                };
                xmlhttp.open("GET", "../php/updateStaffPermissions.php?staffID=" +
                    staffID + "&viewOrderPrivilege=" + getCheckboxValue("viewOrderPrivilege") + "&manageUserPerms=" + getCheckboxValue("manageUserPerms") +
                    "&orderAuth=" + getCheckboxValue("orderAuth"), true);
                xmlhttp.send();
            }

        }

        function getCheckboxValue(id) {
            if (document.getElementById(id).checked == true) {
                return "1";
            } else {
                return "0";
            }

        }

        function updatedNumberOfRecords(number) {
            window.location.replace("AdminDashboard.php?numberOfRecords=" + number + "&page=1&navsearch=<?php echo $postvariable ?>");
        }
    </script>
</head>

<body>
    <?php include 'navbar.php' ?>

    <div id="main">
        <div class="mt-5 pt-3">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Dashboard</h4>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="card">
                            <div class="card-header">
                                <span><i class="bi bi-table me-2"></i></span> Users
                            </div>
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-4">
                                        <label>
                                            Number of users to show
                                        </label>
                                        <select onchange="updatedNumberOfRecords(this.value);" name="records" id="numberOfRecords" aria-controls="example" class="form-select form-select-sm">
                                            <option value="10" <?php if ($numberOfRecords == 10) {
                                                                    echo "selected";
                                                                } ?>>10</option>
                                            <option value="25" <?php if ($numberOfRecords == 25) {
                                                                    echo "selected";
                                                                } ?>>25</option>
                                            <option value="50" <?php if ($numberOfRecords == 50) {
                                                                    echo "selected";
                                                                } ?>>50</option>
                                            <option value="100" <?php if ($numberOfRecords == 100) {
                                                                    echo "selected";
                                                                } ?>>100</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">

                                    </div>
                                    <div class="col-md-4">
                                        <form action="AdminDashboard.php" method="get">
                                            <label>Search:
                                                <input id="navsearch" name="navsearch" type="search" class="form-control form-control-sm" placeholder="Search..." aria-controls="example">
                                            </label>
                                        </form>

                                    </div>
                                </div>


                                <div class="table-responsive">
                                    <table id="example" class="table table-striped data-table" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th scope="col" id="name">Name</th>
                                                <th scope="col" id="role">Role</th>
                                                <th scope="col" id="status">Staff ID</th>
                                                <th scope="col" id="editPermissions">Edit Permissions</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php

                                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                                            ?>

                                                <tr>
                                                    <td><?php echo $row['firstName'] . " " . $row['lastName'] ?></td>
                                                    <td><?php echo $row['jobTitle'] ?></td>
                                                    <td>
                                                        <?php echo $row['staffID'] ?>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary editPermission" onclick="togglePopup('opening','<?php echo $row['staffID'] ?>')">Edit</button>
                                                    </td>
                                                </tr>

                                            <?php
                                            }
                                            ?>

                                        </tbody>

                                </div>

                                </tr>
                                </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                $next = $pageNumber + 1;
                $previous = $pageNumber - 1;
                $first = 1;
                ?>
                <ul class="pagination">
                    <li class="page-item">
                        <?php
                        echo "<a class='page-link' href='AdminDashboard.php?page=1&navsearch=$postvariable&numberOfRecords=$numberOfRecords'>"
                        ?>
                        <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php if ($pageNumber > 1) { // Previous search
                        echo '<li class="page-item">';
                        echo "<a class='page-link' href='AdminDashboard.php?page=$previous&navsearch=$postvariable&numberOfRecords=$numberOfRecords'>Previous</a>";
                        echo '</a>';
                        echo '</li>';
                    } else {
                        echo '<li class="page-item disabled">';
                        echo '<a class="page-link" tabindex="-1" aria-disabled="true">Previous</a>';
                        echo '</li>';
                    }


                    $next2 = $pageNumber + 2; // Current page
                    echo "<li class='page-item active'><a class='page-link' href='AdminDashboard.php?page=$pageNumber&navsearch=$postvariable&numberOfRecords=$numberOfRecords'>" . $pageNumber . "</a></li>";


                    if (((($next - 1) * $numberOfRecords) + 1 > $count)) { // current page +1
                        echo "<li class='page-item disabled'>";
                    } else {
                        echo "<li class='page-item'>";
                    }

                    echo "<a class='page-link' href='AdminDashboard.php?page=$next&navsearch=$postvariable&numberOfRecords=$numberOfRecords'>" . $next . "</a></li>";

                    if (((($next2 - 1) * $numberOfRecords) + 1 > $count)) { //current page +2
                        echo "<li class='page-item disabled'>";
                    } else {
                        echo "<li class='page-item'>";
                    }
                    echo "<a class='page-link' href='AdminDashboard.php?page=$next2&navsearch=$postvariable&numberOfRecords=$numberOfRecords'>" . $next2 . "</a></li>";


                    if (((($next - 1) * $numberOfRecords) + 1 > $count)) { // Next page
                        echo "<li class='page-item disabled'>";
                    } else {
                        echo "<li class='page-item'>";
                    }

                    echo "<a class='page-link' href='AdminDashboard.php?page=$next&navsearch=$postvariable&numberOfRecords=$numberOfRecords'>Next</a>";
                    ?>
                    </li>

                    <li class="page-item">
                        <?php
                        if ($count > $numberOfRecords) {
                            $lastPage = ceil($count / $numberOfRecords);
                            echo "<a class='page-link' href='searchCars.php?page=$lastPage&navsearch=$postvariable&numberOfRecords=$numberOfRecords'>";

                            echo '<span aria-hidden="true">&raquo;</span>';
                            echo '</a>';
                        }
                        ?>

                    </li>

                </ul>
            </div>





            <div class="popup" id="popup-1">
                <div class="overlay"></div>
                <div class="popupContent">
                    <div class="close-btn" onclick="togglePopup('closing')">&times;</div>

                    <div id="loadingSpinnerModal" class="align-middle">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border" role="status">

                            </div>
                        </div>
                        <div class="text-center fs-3 fw-bold align-items-center">
                            Loading...
                        </div>
                    </div>


                    <div id="staffModalData" class="d-none">
                        <table class="table table-hover table-responsive">
                            <thead>
                                <tr>
                                    <th scope="col">Username</th>
                                    <th scope="col">Job role</th>

                                    <th scope="col"></th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="viewOrderPrivilege" value="option1">
                                            <label class="form-check-label" for="inlineCheckbox1"><b>View Order Privilege</b></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="manageUserPerms" value="option2">
                                            <label class="form-check-label" for="inlineCheckbox2"><b>Manage User Permissions</b></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="orderAuth" value="option3">
                                            <label class="form-check-label" for="inlineCheckbox3"><b>Order Authorisation</b></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>

                            </tbody>
                        </table>


                        <div id="updateComplete" class="text-center d-none">
                            Update successful, press the X in the top right to close this
                        </div>

                        <button class="btn btn-primary" id="saveChangesToPermissions"> Save Changes </button>

                    </div>

                </div>
            </div>
        </div>
    </div>


    <script>
        document.getElementById("currentPage").innerHTML = "Admin Dashboard";
    </script>

</body>