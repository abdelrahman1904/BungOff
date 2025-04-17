<?php
class Vehicule {
    private $type, $model, $matricule, $capacite, $dispo;

    public function __construct($type, $model, $matricule, $capacite, $dispo) {
        $this->type = $type;
        $this->model = $model;
        $this->matricule = $matricule;
        $this->capacite = $capacite;
        $this->dispo = $dispo;
    }

    public function gettype() { return $this->type; }
    public function getModel() { return $this->model; }
    public function getmatricule() { return $this->matricule; }
    public function getCapacite() { return $this->capacite; }
    public function getDispo() { return $this->dispo; }
    public function setIdVehicule($id) {
        $this->id_vehicule = $id;
    }
}
?>
