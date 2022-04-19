<?php
include "../Functions/console_log.php";
include "../Functions/checkLogin.php";
include "../Functions/redirect.php";
include "dataBaseConnection.php";
session_start();
$user_data = check_login($pdo);
if (isset($user_data)) { // if the user is not logged in, redirect them to the login page
  redirect('Dashboard.php');
}




$displayInvalidPassword = "false";
$displayInvalidUsername = "false";

if (isset($_GET['username'])) {
  $displayInvalidUsername = $_GET['username'];
}
if (isset($_GET['password'])) {
  $displayInvalidPassword = $_GET['password'];
}
?>

<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/Login.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <title>Login</title>
</head>

<body>

  <form action="../php/signin.php" method="post" class="loginForm">
    <div class="imgcontainer">
      <img src="../Images/G4u.png" alt="Avatar" class="avatar">
    </div>

    <div class="container">
      <label for="uname"><b>Username</b></label>
      <input type="text" placeholder="Enter Username" name="username" required>

      <?php
      if ($displayInvalidUsername == "true") {
        echo "<div class='container-fluid'>Incorrect Username</div>";
      }
      ?>

      <label for="psw"><b>Password</b></label>
      <input type="password" placeholder="Enter Password" name="password" required>
      <div class="container-fluid">
        <?php
        if ($displayInvalidPassword == "true") {
          echo "Incorrect Password";
        }
        ?>
      </div>
      <button type="submit" class="btn btn-primary mb-3">Login</button>
      <label>
        <input type="checkbox" checked="checked" name="remember"> Remember me
      </label>
    </div>
  </form>
</body>

</html>