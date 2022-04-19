<?php
include "redirect.php";

function checkLoggedIn(){
    session_start();
    if(isset($_SESSION['userID'])) // If we have an active session, then return all the data related to that user
        {
            redirect("HomePage.html");
        }
}
