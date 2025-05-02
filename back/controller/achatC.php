<?php 
require '../config.php';
class achatC   {
    
    public function addachat($achat)
    {
      $sql = "INSERT INTO achat VALUES (NULL, :noma, :cina, :matriculea, :marquea, :typ, :prixa, :datea)";
      $db = config::getConnexion();
      try {
        $query = $db->prepare($sql);
        $query->execute([
          "noma" => $achat->getnoma(),
          "cina" => $achat->getcina(),
          "matriculea" => $achat->getmatriculea(),
          "marquea" => $achat->getmarquea(),
          "typ" => $achat->gettyp(),
          "prixa" => $achat->getprixa(),
          "datea" => $achat->getdatea(),
        ]);
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    }
  
    public function updateachat($achat,$id)
    {
      $sql = "UPDATE achat SET noma=:noma,cina=:cina, matriculea=:matriculea,marquea=:marquea,typ=:typ, prixa=:prixa,datea=:datea WHERE id = :id";
      $db = config::getConnexion();
      try {
        $query = $db->prepare($sql);
        $query->execute([
          "id" => $id,
          "noma" => $achat->getnoma(),
          "cina" => $achat->getcina(),
          "matriculea" => $achat->getmatriculea(),
          "marquea" => $achat->getmarquea(),
          "typ" => $achat->gettyp(),
          "prixa" => $achat->getprixa(),
          "datea" => $achat->getdatea(),
        ]);
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    }
  
    public function deleteachat($id)
    {
      $sql = "DELETE FROM achat WHERE id = :id";
      $db = config::getConnexion();
      try {
        $query = $db->prepare($sql);
        $query->execute([
          "id" => $id,
        ]);
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    }

    public function allachat()
    {
      $sql = "SELECT * FROM achat";
      $db = config::getConnexion();
      try {
        $query = $db->prepare($sql);
        $query->execute();
        $service = $query->fetch();
        $res = [];
        for ($x = 0; $service; $x++) {
          $res[$x] = $service;
          $service = $query->fetch();
        }
        return $res;
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    }

    public function findachat($id)
    {
      $sql = "SELECT * FROM achat WHERE id = :id";
      $db = config::getConnexion();
      try {
        $query = $db->prepare($sql);
        $query->execute([
          "id" => $id,
        ]);
        $service = $query->fetch();
  
        return $service;
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    }
    function rechercheachat($achat){
      $sql = "SELECT * FROM achat WHERE nom = :nom";
      $db = config::getConnexion();
      try{
      $liste=$db->query($sql);
      $r=$liste;
      return $r;
      }
          catch (Exception $e){
              die('Erreur: '.$e->getMessage());
          }	
    }
}
?>


