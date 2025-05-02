<?php
// Reservation.php

class reservation {
    private $ID;
    private $IDB;  // ID du bungalow
    private $date_arrive;
    private $date_depart;
    private $nbp;  // Nombre de personnes
    private $prix_total;
    private $nom_bungalow ;

    // Constructeur
    public function __construct($IDB, $date_arrive, $date_depart, $nbp, $prix_nuit) {
        $this->IDB = $IDB;
        $this->date_arrive = $date_arrive;
        $this->date_depart = $date_depart;
        $this->nbp = $nbp;

        // Calcul du prix total en fonction de la durée du séjour et du prix par nuit
        $this->prix_total = $this->calculerPrixTotal($prix_nuit);
    }

    // Méthode pour calculer le prix total
    public function calculerPrixTotal($prix_nuit) {
        // Calcul de la différence entre la date de départ et d'arrivée en jours
        $date_arrive = new DateTime($this->date_arrive);
        $date_depart = new DateTime($this->date_depart);
        $diff = $date_arrive->diff($date_depart);
        $nights = $diff->days;

        // Calcul du prix total
        return $prix_nuit * $nights;
    }

    // Getter pour IDB
    public function getIDB() {
        return $this->IDB;
    }
    public function getNomBungalow() {
        return $this->nom_bungalow;
    }

    public function getDateArrive() {
        return $this->date_arrive;
    }

    public function getDateDepart() {
        return $this->date_depart;
    }

    public function getNbp() {
        return $this->nbp;
    }

    public function getPrixTotal() {
        return $this->prix_total;
    }

    // Optionally, add setters if you need them
    public function setDateArrive($date_arrive) {
        $this->date_arrive = $date_arrive;
    }

    public function setDateDepart($date_depart) {
        $this->date_depart = $date_depart;
    }

    public function setNbp($nbp) {
        $this->nbp = $nbp;
    }

    public function setPrixTotal($prix_total) {
        $this->prix_total = $prix_total;
    }
    public function setNomBungalow($nom_bungalow) {
        $this->nom_bungalow = $nom_bungalow;
    }
}
?>
