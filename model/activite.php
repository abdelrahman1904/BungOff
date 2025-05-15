<?php
class Activite {
    private $titre, $guide, $description, $duree, $type, $prix, $photo, $nbp, $id_user;

    public function __construct($titre, $guide, $description, $duree, $type, $prix, $photo, $nbp) {
        $this->titre = $titre;
        $this->guide = $guide;
        $this->description = $description;
        $this->duree = $duree;
        $this->type = $type;
        $this->prix = $prix;
        $this->photo = $photo;
        $this->nbp = $nbp;
    }

    public function getTitre() { return $this->titre; }
    public function getGuide() { return $this->guide; }
    public function getDescription() { return $this->description; }
    public function getDuree() { return $this->duree; }
    public function getType() { return $this->type; }
    public function getPrix() { return $this->prix; }
    public function getPhoto() { return $this->photo; }
    public function getNbp() { return $this->nbp; }
}
?>
