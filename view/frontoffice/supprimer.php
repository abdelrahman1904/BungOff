<?php
require_once '../../controller/aviscontroller.php';
$aviscontroller= new AvisController();
$aviscontroller->deleteAvis($_POST['IDUtilisateur']); 
header('Location:index.php');

?>