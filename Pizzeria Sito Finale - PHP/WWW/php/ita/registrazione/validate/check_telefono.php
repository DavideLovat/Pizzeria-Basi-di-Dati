<?php
if(isset($_GET['telefono']) && is_numeric($_GET['telefono']))
{
	echo 'true';
	exit;
}
else
{
	echo 'false';
}
?>