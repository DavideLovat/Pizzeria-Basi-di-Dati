<?php
	session_start();
	header('content-Type: text/html;charset=UTF-8');

	require_once("php/ita/shared/credenziali_db.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="Your Company" />
	<meta name="keywords" content="Your Company" />
	<meta name="description" content="Your Company" />
	<meta name="robots" content="all" />
<!-- inzio script aggiunti -->	
	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
	<script type="text/javascript" src="http://wwwstud.dsi.unive.it/dlovat/WWW/js/lista-pizze/lista-pizze.js"></script>	

	<link rel="stylesheet" type="text/css" href="http://wwwstud.dsi.unive.it/dlovat/WWW/css/shared/global-1.css">
	

<!-- fine script aggiunti -->	
	<title>Chalk Rock</title>
</head>

<body>
<div id="wrapper">
	<div id="header">
    	<h1><span class="black">Pizzeria</span> <span class="orange">Online</span></h1>
        <h2>Your Slogan</h2>
    </div>
    <div id="navi">
    	<ul>
        	<li class="active"><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/">Home</a></li>
        	<li><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/lista-pizze/">Lista Pizze</a></li>
        	<li><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/carrello/">Carrello</a></li>
			<?php
			if(isset($_SESSION['utente']['login'],$_SESSION['utente']['nome']) && !empty($_SESSION['utente']['nome']) && !empty($_SESSION['utente']['login']))

			{	
				echo "<li><a href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/ordini/\">Ordini</a></li>";
			}
			?>

			<?php
			if(isset($_SESSION['amministratore']) && !empty($_SESSION['amministratore']))
			{	
				echo "<li><a href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso_amm/index.php\">Amministratore</a></li>";
			}
			?>

			<li style="float:right;">
			<?php 
			if(isset($_GET['ordina']))
			{
				switch($_GET['ordina'])
				{
				case "tipo":
					$ordina = "tipo";
				break;
				case "prezzo":
					$ordina = "prezzo";
				break;
				default: $ordina = "tipo";			
				}
			}
			else
			{
				$ordina = "tipo";
			}
			echo "
			<form action=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/lista-pizze/\" method=\"get\">
		  		Cerca Pizza: <input type=\"search\" name=\"string\">
				<input type=\"hidden\" name=\"ordina\" value=\"$ordina\" />
			  	<input type=\"submit\">
			</form>
			";
			?>
			</li>
        </ul>
    </div>
    <div id="subnavi">
    	<ul>
        	<!-- <li class="active"><a href="#">Item</a></li> -->
		<?php
		if(isset($_SESSION['utente']['login'],$_SESSION['utente']['nome']) && !empty($_SESSION['utente']['nome']) && !empty($_SESSION['utente']['login']))
		{	
			echo "<li  class=\"active\"><a href=\"../shared/log_out.php?doLogout=true\">Log out {$_SESSION['utente']['nome']}</a></li>";
		}
		else
		{
			echo "
				<li><a href=\"http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/accesso/\">Accedi</a></li>
			";
		}
		?>
	<li style="float:right;"><a href="http://wwwstud.dsi.unive.it/dlovat/WWW/php/ita/registrazione/">Registrati</a></li>
        </ul>
    </div>
		
    <div id="main">
	 
        <div id="content">
            <h1><span>H1 Headline</span></h1>
            <img src="http://wwwstud.dsi.unive.it/dlovat/WWW/images/home/pizzeria.jpg" alt="Header Image" width="568" height="220" class="frame" />
          <p>"Chalk Rock" is a free, tableless, W3C-compliant web design layout by MediaUp. This template has been tested and proven compatible with all major browser environments and operating systems. You are free to modify the design to suit your tastes in any way you like.We only ask you to not remove "Schlafzimmer komplett" and the link <a href="http://www.schlafzimmerkomplett.net/" target="_blank">http://www.schlafzimmerkomplett.net/</a> from the footer of the template. Have fun! ;)</p>
            
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Category</a></li>
                <li><a href="#">Category</a></li>
                <li><a href="#">Category</a></li>
                <li><a href="#">Category</a></li>
                <li><a href="#">Category</a></li>
                <li><a href="#">Category</a></li>
                <li><a href="#">Category</a></li>
            </ul>
            
            <h2><span>H2 Headline</span></h2>
            
            <p>Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur 
            sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero 
            eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit 
            amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna 
            aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
            
            <p>Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat.</p>
        </div>
		
		<div id="news">
        	<h1>News</h1>
            <div class="item">
                <p><small>21.03.1977</small><br />
                <b>News</b><br>
                Sit no laudem noster imperdiet. Amet vide ei qui. Ei perpetua gubergren vim, movet accusam no mei, at stet eripuit eum.<br /> <a href="#">&raquo; more</a></p>
            </div>
            <div class="item">
                <p><small>21.03.1977</small><br />
                <b>News</b><br>
                Sit no laudem noster imperdiet. Amet vide ei qui. Ei perpetua gubergren vim, movet accusam no mei, at stet eripuit eum.<br /> <a href="#">&raquo; more</a></p>
            </div>
            <div class="item">
                <p><small>21.03.1977</small><br />
                <b>News</b><br>
                Sit no laudem noster imperdiet. Amet vide ei qui. Ei perpetua gubergren vim, movet accusam no mei, at stet eripuit eum.<br /><a href="#">&raquo; more</a></p>
            </div>
        </div>
		
    </div>
	
    <div id="footer">
        <p>© Copyright 2012 yourname.com. All Rights Reserved.  |  Design by <a href="http://www.schlafzimmerkomplett.net/" target="_blank">Schlafzimmer komplett</a></p>
    </div>
    <div class="cleaner"></div>
</div>
</body>
</html>
