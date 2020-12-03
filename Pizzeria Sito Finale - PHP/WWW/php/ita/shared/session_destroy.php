<?php
session_start();

session_destroy();

header("Location: http://wwwstud.dsi.unive.it/dlovat/Progetto%20Pizzeria%20on-line/form_ordinazioni.php");

?>