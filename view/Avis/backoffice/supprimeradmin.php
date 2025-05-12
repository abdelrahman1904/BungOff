<?php
require_once __DIR__.'/../../../controller/aviscontroller.php';
$aviscontroller= new AvisController();
$aviscontroller->deleteAvis($_POST['IDUtilisateur']); 
header('Location:index.php');

?>