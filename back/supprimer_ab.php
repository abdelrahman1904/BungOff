<?php 

$id = $_GET['id'];
include_once "../controller/userlistC.php";

$userC= new userlistC();
$userC->deleteuser($id); 
header('Location:afficherab.php');




?>