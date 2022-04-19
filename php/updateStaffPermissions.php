<?php
include "../Pages/databaseConnection.php";


if (
    isset($_GET['staffID']) && isset($_GET['viewOrderPrivilege'])
    && isset($_GET['manageUserPerms'])
    && isset($_GET['orderAuth'])
) {
    $id = $_GET['staffID'];
    $viewOrderPrivilege = $_GET['viewOrderPrivilege'];
    $manageUserPerms = $_GET['manageUserPerms'];
    $orderAuth = $_GET['orderAuth'];

    $query = "UPDATE `staff`
    SET viewOrderPrivilege =?,managingUserPermsAuthorisation=?,orderAuthPermission=?
    WHERE staffID = ?";
    $update = $pdo->prepare($query);
    $update->execute([$viewOrderPrivilege,$manageUserPerms,$orderAuth,$id]);
}
