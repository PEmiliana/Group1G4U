<?php $user_data = check_login($pdo); ?>
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <div class="username">
        <?php
        echo $user_data['staffID']
        ?></div>
    <a href="#" class="notification">

        <?php
        $navbaruniquequery = "SELECT * FROM product";
        $navbaruniquestmt = $pdo->prepare($navbaruniquequery);
        $navbaruniquestmt->execute();
        $notificationNumber = 0;
        while ($navbaruniquerow = $navbaruniquestmt->fetch(PDO::FETCH_ASSOC)) {
            $productName = $navbaruniquerow['productName'];
            if ($navbaruniquerow['stock'] <= $navbaruniquerow['stockToReorderAt']) {

                $notificationNumber++;
            } else if ($navbaruniquerow['stock'] < ($navbaruniquerow['stockToReorderAt'] + 200) && $navbaruniquerow['stock'] > $navbaruniquerow['stockToReorderAt']) {
                $notificationNumber++;
            }
        }
        ?>
        <?php
        if ($notificationNumber > 0) {
        ?>
            <span>Notification</span>
            <span class="badge"><?php echo $notificationNumber ?></span>
        <?php
        }

        ?>




    </a>
    <a href="Dashboard.php">Home</a>
    <a href="Stock.php">Stock</a>
    <a href="CreateOrder.php">Create Order</a>
    <a href="Orders.php">Orders</a>

    <?php
    if ($user_data['managingUserPermsAuthorisation'] == 1) {
        echo '<a href="AdminDashboard.php">Admin Panel</a>';
    }
    ?>
    <a href="../php/signout.php">Sign out</a>

</div>
<!-- Use any element to open the sidenav -->
<div class="float-start mx-3" style="font-size: 60px;color: black;" id="menuButton">

    <div class="bi bi-list fs-1 position-relative" id="menuIcon" onclick="openNav()">
        <span id="currentPage" class="currentPage position-absolute top-50 start-50 translate-middle">Current Page</span>
    </div>

</div>

<script>
    /* Set the width of the side navigation to 250px */
    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    /* Set the width of the side navigation to 0 */
    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }
    /* Set the width of the side navigation to 250px and the left margin of the page content to 250px */
    function openNav() {
        if (screen.width < 960) {
            document.getElementById("mySidenav").style.width = "100%";
        } else {

            document.getElementById("mySidenav").style.width = "250px";
        }
        document.getElementById("main").style.marginLeft = "250px";
    }

    /* Set the width of the side navigation to 0 and the left margin of the page content to 0 */
    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
        document.getElementById("main").style.marginLeft = "0";
    }
</script>