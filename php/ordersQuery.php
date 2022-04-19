<?php
include "../Pages/databaseConnection.php";
include "../Functions/checkLogin.php";
session_start();
$user_data = check_login($pdo);
$count = 0;
$countAND = 0;
if (isset($_GET['endDate'])) {
    $endDate = $_GET['endDate'];
    $count++;
}
if (isset($_GET['startDate'])) {
    $startDate = $_GET['startDate'];
    $count++;
}
if (isset($_GET['orderStatus'])) {
    $orderStatus = $_GET['orderStatus'];
    $count++;
}

$query = "SELECT * FROM `order`";
if ($count > 1) {
    $query .= " WHERE";

    if (!empty($endDate)) {
        $endDateSQLFormat = date("Y-m-d H:i:s", strtotime($endDate));
        $query .= " `orderDate` <= '$endDate'";
        $countAND++;
        if ($countAND < $count) {
            $query .= " AND";
        }
    }
    if (!empty($startDate)) {
        $startDateSQLFormat = date("Y-m-d H:i:s", strtotime($startDate));
        $query .= " `orderDate` >= '$startDateSQLFormat'";
        $countAND++;
        if ($countAND < $count) {
            $query .= " AND";
        }
    }
    if (!empty($orderStatus)) {
        $query .= " `state` = '$orderStatus'";
        $countAND++;
    }
}
if ($user_data['orderAuthPermission'] == 0 || $user_data['viewOrderPrivilege'] == 0) {
    $query .= " AND staffID='" . $user_data['staffID']."';";
}


$filter = $pdo->prepare($query);
$filter->execute([]);
while ($row = $filter->fetch(PDO::FETCH_ASSOC)) {
    $orderID = $row['orderID'];
    echo
    "<tr>
       <td>" . $row['orderID'] . "</td>
        <td>" . date_format(date_create($row['orderDate']), "d/m/Y") . "</td>
        <td>" . $row['state'] . "</td>
        <td>" . $row['staffID'] . "</td>
        <td>
        <a href='FormDetails.php?orderID=$orderID'>
          <button type='button' class='btn btn-primary'>
          View
          </button>
          </a>
        </td>
      </tr>";
}
