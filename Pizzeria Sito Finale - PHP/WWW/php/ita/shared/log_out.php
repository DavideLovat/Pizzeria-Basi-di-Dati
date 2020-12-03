<?php 
//initialize the session 
// ** Logout the current user. **

session_start();
echo "<br> inizio sessione: ".print_r($_SESSION,true)."<br>";

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")) {
    // to fully log out a visitor we need to clear the session variables
    session_destroy();	
    $logoutGoTo = "http://wwwstud.dsi.unive.it/dlovat/WWW/";

    header("Location: $logoutGoTo");
    exit;
    
} 
echo "<br> fine sessione: ".print_r($_SESSION,true)."<br>";
?>