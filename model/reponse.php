<?php
class Reponse {
    private $id_reponse;
    private $poste_admin;
    private $reponse_admin;
    private $date_reponse;
    private $IDUtilisateur;
    private $id_avis; // Ajouté pour la jointure avec la table avis

    public function __construct($poste_admin, $reponse_admin, $date_reponse, $id_reponse = null) {
        $this->id_reponse = $id_reponse;
        $this->poste_admin = $poste_admin;
        $this->reponse_admin = $reponse_admin;
        $this->date_reponse = $date_reponse;
    }

    // Getters
    public function getIdReponse() {
        return $this->id_reponse;
    }

    public function getPosteAdmin() {
        return $this->poste_admin;
    }

    public function getReponseAdmin() {
        return $this->reponse_admin;
    }

    public function getDateReponse() {
        return $this->date_reponse;
    }

    // Setters
    public function setIdReponse($id_reponse) {
        $this->id_reponse = $id_reponse;
    }

    public function setPosteAdmin($poste_admin) {
        $this->poste_admin = $poste_admin;
    }

    public function setReponseAdmin($reponse_admin) {
        $this->reponse_admin = $reponse_admin;
    }

    public function setDateReponse($date_reponse) {
        $this->date_reponse = $date_reponse;
    }

    public function setIDUtilisateur($IDUtilisateur) {
        $this->IDUtilisateur = $IDUtilisateur;
    }

    public function setIdAvis($id_avis) {
        $this->id_avis = $id_avis;
    }

    // Méthode pour convertir l'objet en tableau (utile pour PDO)
    public function toArray() {
        return [
            'id_reponse' => $this->id_reponse,
            'poste_admin' => $this->poste_admin,
            'reponse_admin' => $this->reponse_admin,
            'date_reponse' => $this->date_reponse,
            'IDUtilisateur' => $this->IDUtilisateur,
            'id_avis' => $this->id_avis
        ];
    }
}
?>