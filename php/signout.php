<?php
session_start();
include "../Functions/redirect.php";

if(isset($_SESSION['staffID']))
{
    unset($_SESSION['staffID']);
}
redirect("../Pages/Index.php");
die;