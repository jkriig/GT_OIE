<?php
require_once("GTED.php");
$gted = new GTED();
$gtedInfo = $gted->getUser($_POST['gtid']);
$name = $gtedInfo["displayname"]["0"];
echo json_encode($name);				 
?>