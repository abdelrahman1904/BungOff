<?php
class Avis {
    private $IDUtilisateur;
    private $Nom;
    private $LieuDuBungalow;
    private $ActivitéUtilisée;
    private $Note;
    private $Commentaire;

    public function __construct($Nom, $LieuDuBungalow, $ActivitéUtilisée, $Note, $Commentaire, $IDUtilisateur = null) {
        $this->IDUtilisateur = $IDUtilisateur;
        $this->Nom = $Nom;
        $this->LieuDuBungalow = $LieuDuBungalow;
        $this->ActivitéUtilisée = $ActivitéUtilisée;
        $this->Note = $Note;
        $this->Commentaire = $Commentaire;
    }
    // Getters
    public function getIDUtilisateur() {
        return $this->IDUtilisateur;
    }

    public function getNom() {
        return $this->Nom;
    }

    public function getLieuDuBungalow() {
        return $this->LieuDuBungalow;
    }

    public function getActivitéUtilisée() {
        return $this->ActivitéUtilisée;
    }

    public function getNote() {
        return $this->Note;
    }

    public function getCommentaire() {
        return $this->Commentaire;
    }

    // Setters
    public function setIDUtilisateur($IDUtilisateur) {
        $this->IDUtilisateur = $IDUtilisateur;
    }

    public function setNom($Nom) {
        $this->Nom = $Nom;
    }

    public function setLieuDuBungalow($LieuDuBungalow) {
        $this->LieuDuBungalow = $LieuDuBungalow;
    }

    public function setActivitéUtilisée($ActivitéUtilisée) {
        $this->ActivitéUtilisée = $ActivitéUtilisée;
    }

    public function setNote($Note) {
        $this->Note = $Note;
    }

    public function setCommentaire($Commentaire) {
        $this->Commentaire = $Commentaire;
    }
}
?>