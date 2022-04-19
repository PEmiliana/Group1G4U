<?php
include "../Pages/databaseConnection.php";


if (!empty($_GET['staffID'])) {
    $staffID = $_GET['staffID'];
    $query = "SELECT viewOrderPrivilege,managingUserPermsAuthorisation,orderAuthPermission FROM staff
    WHERE staffID=? LIMIT 1";

    $getPerms = $pdo->prepare($query);
    $getPerms->execute([$staffID]);
    $permissions = $getPerms->fetch(PDO::FETCH_ASSOC);
    $viewOrder = $permissions['viewOrderPrivilege'];
    $managePerms = $permissions['managingUserPermsAuthorisation'];
    $orderAuth = $permissions['orderAuthPermission'];


    echo "$viewOrder#$managePerms#$orderAuth";
} else {
    echo "0#0#0";
}
