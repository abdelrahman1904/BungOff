<?php
require_once __DIR__.'/../../controller/reponsecontroller.php';
$aviscontroller= new ReponseController();
$aviscontroller->delete($_POST['idreponse']); 
header('Location:index.php');

?>