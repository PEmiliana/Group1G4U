<?php
function check_login($pdo)
{
    if(isset($_SESSION['staffID'])) // If we have an active session, then return all the data related to that user
    {
        $userid = $_SESSION['staffID'];
        $query= $pdo->prepare("SELECT * FROM staff WHERE staffID=? LIMIT 1");
        $query->execute([$userid]);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        if($result)
        {
            return $result;
        }

    }
    
    
}
?>