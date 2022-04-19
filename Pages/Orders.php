<?php
include "databaseConnection.php";
include "../Functions/checkLogin.php";
include "../Functions/console_log.php";
include "../Functions/redirect.php";
session_start();
$user_data = check_login($pdo);
if (!isset($user_data)) { // if the user is not logged in, redirect them to the login page
  redirect('Index.php');
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/Sidebar.css">
  <link rel="stylesheet" href="../css/Everything.css">
  <link rel="stylesheet" href="../css/Orders.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.0/font/bootstrap-icons.min.css" integrity="sha512-H4E1ASW8Ru1Npd1wQPB7JClskV8Nv1FG/bXK6TWMD+U9YMlR+VWUZp7SaIbBVBV/iRtefsIsv9dLSN6fdUI36w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Orders</title>
  <script>
    var locked = false;

    function filterOrders() {
      if (locked == false) {
        locked = true;
        var xmlhttp = new XMLHttpRequest();
        var orderStatusVal = document.getElementById("orderStatus").value;
        var startDateVal = document.getElementById("startDate").value;
        var endDateVal = document.getElementById("endDate").value;
        if (endDateVal) {
          endDateVal = endDateVal + " 00:00:00";
        }
        if (startDateVal) {
          startDateVal = startDateVal + " 00:00:00";
        }

        xmlhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("bodyOfOrders").innerHTML = this.responseText;
            locked = false;
          }
        };
        xmlhttp.open("GET",
          "../php/ordersQuery.php?orderStatus=" + orderStatusVal +
          "&endDate=" + endDateVal +
          "&startDate=" + startDateVal, true);
        xmlhttp.send();
      }

    }
  </script>
</head>

<body>
  <?php include 'navbar.php' ?>
  <div id="main">


    <div class="row align-items-end">

      <div class="form-group mb-2 col-md-3 ">
        <h4>From</h4>
        <input id="startDate" class="form-inline" name="FilterFromDate" type="date" placeholder="From">
      </div>

      <div class="form-group mb-2 col-md-3">
        <h4>To</h4>
        <input id="endDate" class="form-inline" name="FilterToDate" type="date" placeholder="To">
      </div>

      <div class="form-group mb-2 col-md-2">
        <h4>Order status</h4>
        <select id="orderStatus" class="form-control form-control-sm" name="selectOrderStatus">
          <option value="Pending">Pending</option>
          <option value="Approved">Approved</option>
          <option value="Declined">Declined</option>
        </select>
      </div>
      <div class="col-1"></div>
      <div class="col-md-2">
        <button onclick="filterOrders();" type="button" class="btn btn-primary w-100">Filter</button>
      </div>
    </div>

    <div class="table-responsive mt-3">
      <table class="table table-hover text-center table-striped">
        <thead>
          <tr>
            <th id="stock-header" scope="col">#Order ID</th>
            <th id="stock-header" scope="col">Order Date</th>
            <th id="stock-header" scope="col">Status</th>
            <th id="stock-header" scope="col">Request By</th>
            <th id="stock-header" scope="col"></th>

          </tr>
        </thead>
        <tbody id="bodyOfOrders">

          <?php
          if ($user_data['orderAuthPermission'] == 1 || $user_data['viewOrderPrivilege'] == 1) {
            $query = "SELECT * FROM `order`";
          } else {
            $query = "SELECT * FROM `order` WHERE staffID='" . $user_data['staffID']."';";
          }
          $stmt = $pdo->prepare($query);
          $stmt->execute();
          while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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
          ?>
        </tbody>
      </table>
    </div>

  </div>
  <script>
    document.getElementById("currentPage").innerHTML = "Orders";
  </script>
</body>