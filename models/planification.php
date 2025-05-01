<?php
class Planification {
    private $idp; // auto-incrémenté, donc non nécessaire dans le constructeur
    private $lieu;
    private $date;
    private $heure_debut;
    private $heure_fin;
    private $capacite;
    private $nom_activite;

    public function __construct($lieu, $date, $heure_debut, $heure_fin, $capacite, $nom_activite) {
        $this->lieu = $lieu;
        $this->date = $date;
        $this->heure_debut = $heure_debut;
        $this->heure_fin = $heure_fin;
        $this->capacite = $capacite;
        $this->nom_activite = $nom_activite;
    }

    // Getters
    public function getIdp() { return $this->idp; }
    public function getLieu() { return $this->lieu; }
    public function getDate() { return $this->date; }
    public function getHeureDebut() { return $this->heure_debut; }
    public function getHeureFin() { return $this->heure_fin; }
    public function getCapacite() { return $this->capacite; }
    public function getNomActivite() { return $this->nom_activite; }

    // Setters (si besoin)
    public function setIdp($idp) { $this->idp = $idp; }
    public function setLieu($lieu) { $this->lieu = $lieu; }
    public function setDate($date) { $this->date = $date; }
    public function setHeureDebut($heure_debut) { $this->heure_debut = $heure_debut; }
    public function setHeureFin($heure_fin) { $this->heure_fin = $heure_fin; }
    public function setCapacite($capacite) { $this->capacite = $capacite; }
    public function setNomActivite($nom_activite) { $this->nom_activite = $nom_activite; }
}
?>
