<?php
include "../Functions/redirect.php";
include "../Pages/databaseConnection.php";
include "../Functions/console_log.php";
session_start();
if ($_SERVER['REQUEST_METHOD'] == "POST") { // Get what was posted
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (!empty($username) && !empty($password)) { // Check the user entered something
        $query = $pdo->prepare("SELECT * FROM staff WHERE staffID ='" . $username . "' AND password='" . $password . "' LIMIT 1");
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);



        if ($query->rowCount() > 0) { // If the query got any results
            $user_data = $result;

            if ($user_data['password'] == $password) { // If what the user entered matched the database
                $_SESSION['staffID'] = $user_data['staffID']; // Create a session that logs the user in
                redirect('../Pages/Dashboard.php');// Redirect the user to the homepage
                
            }
            else if($user_data['password']!=$password) 
            {
                //Only happens when just password is incorrect
               redirect("../Pages/Index.php?username=false&password=true");
            }
            else{
                //When both are incorrect
                redirect("../Pages/Index.php?username=true&password=true");
            }
        }
        else{
            // When password is incorrect
            redirect("../Pages/Index.php?username=false&password=true");
        }
    }

}

?>