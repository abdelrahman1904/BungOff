<?php
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../Models/promotion_model.php';

class PromotionController {
    private $model;

    public function __construct() {
        $this->model = new Promotion();
    }

    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'list';

        switch ($action) {
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $titreP = $_POST['titreP'];
                    $descriptionP = $_POST['descriptionP'];
                    $pourcentage = $_POST['pourcentage'];
                    $codePromo = $_POST['codePromo'] ?: null;
                    $date_debutP = $_POST['date_debutP'];
                    $date_finP = $_POST['date_finP'];
                    $idC = $_POST['idC'];
                    if ($this->model->create($titreP, $descriptionP, $pourcentage, $codePromo, $date_debutP, $date_finP, $idC)) {
                        header('Location: manage_promotion.php?success=Promotion créée');
                        exit();
                    } else {
                        header('Location: manage_promotion.php?error=Erreur lors de la création');
                        exit();
                    }
                }
                break;

            case 'edit':
                $idP = $_GET['idP'];
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $titreP = $_POST['titreP'];
                    $descriptionP = $_POST['descriptionP'];
                    $pourcentage = $_POST['pourcentage'];
                    $codePromo = $_POST['codePromo'] ?: null;
                    $date_debutP = $_POST['date_debutP'];
                    $date_finP = $_POST['date_finP'];
                    $idC = $_POST['idC'];
                    if ($this->model->update($idP, $titreP, $descriptionP, $pourcentage, $codePromo, $date_debutP, $date_finP, $idC)) {
                        header('Location: manage_promotion.php?success=Promotion mise à jour');
                        exit();
                    } else {
                        header('Location: manage_promotion.php?error=Erreur lors de la mise à jour');
                        exit();
                    }
                }
                break;

            case 'delete':
                $idP = $_GET['idP'];
                if ($this->model->delete($idP)) {
                    header('Location: manage_promotion.php?success=Promotion supprimée');
                    exit();
                } else {
                    header('Location: manage_promotion.php?error=Erreur lors de la suppression');
                    exit();
                }
                break;

            case 'list':
            default:
                return $this->model->readAll();
        }
    }

    public function getPromotion($idP) {
        return $this->model->read($idP);
    }
}
?>