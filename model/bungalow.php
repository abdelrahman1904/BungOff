<?php
class Bungalow {
    private $IDB, $nom, $capacite, $prix_nuit, $localisation, $type, $description, $image;

    public function __construct( $nom, $capacite, $prix_nuit, $localisation, $type, $description, $image) {
        $this->nom = $nom;
        $this->capacite = $capacite;
        $this->prix_nuit = $prix_nuit;
        $this->localisation = $localisation;
        $this->type = $type;
        $this->description = $description;
        $this->image = $image;
    }

    public function getNom() { return $this->nom; }
    public function getCapacite() { return $this->capacite; }
    public function getPrixNuit() { return $this->prix_nuit; }
    public function getLocalisation() { return $this->localisation; }
    public function getType() { return $this->type; }
    public function getDescription() { return $this->description; }
    public function getImage() { return $this->image; }
}
?>
