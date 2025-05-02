<?php 
require '../config.php';
class locationC   {
    
    public function addlocation($location)
    {
      $sql = "INSERT INTO location 
         VALUES (NULL,:nomf,:cinf, :matricule, :marque, :prix, :dated, :datef)";
      $db = config::getConnexion();
      try {
        $query = $db->prepare($sql);
        $query->execute([
          "nomf" => $location->getnomf(),
          "cinf" => $location->getcinf(),
          "matricule" => $location->getmatricule(),
          "marque" => $location->getmarque(),
          "prix" => $location->getprix(),
          "dated" => $location->getdated(),
          "datef" => $location->getdatef(),
        ]);
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    }
  
    public function updatelocation($location,$id)
    {
      $sql =
        "UPDATE location SET nomf=:nomf,cinf=:cinf, matricule=:matricule,marque=:marque, prix=:prix,dated=:dated, datef=:datef WHERE id = :id";
      $db = config::getConnexion();
      try {
        $query = $db->prepare($sql);
        $query->execute([
          "id" => $id,
          "nomf" => $location->getnomf(),
          "cinf" => $location->getcinf(),
          "matricule" => $location->getmatricule(),
          "marque" => $location->getmarque(),
          "prix" => $location->getprix(),
          "dated" => $location->getdated(),
          "datef" => $location->getdatef(),
        ]);
      } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
      }
    }
  
    public function deletelocation($id)
    {
      $sql = "DELETE FROM location WHERE id = :id";
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

    public function alllocation()
    {
      $sql = "SELECT * FROM location";
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

    public function findlocation($id)
    {
      $sql = "SELECT * FROM location WHERE id = :id";
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
}
?>


