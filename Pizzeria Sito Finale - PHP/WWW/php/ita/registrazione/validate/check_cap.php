<?php
if(isset($_GET['cap']) && is_numeric($_GET['cap']) && strlen($_GET['cap'])==5)
{
	echo 'true';
	exit;
}
else
{
	echo 'false';
}
?>