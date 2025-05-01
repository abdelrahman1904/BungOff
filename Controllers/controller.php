<?php
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../Models/model.php';

class CompagneController {
    private $model;

    public function __construct() {
        $this->model = new Compagne();
    }

    public function handleRequest() {
        $action = isset($_GET['action']) ? $_GET['action'] : 'list';

        switch ($action) {
            case 'create':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $nom = $_POST['nom'];
                    $description = $_POST['description'];
                    $date_debut = $_POST['date_debut'];
                    $date_fin = $_POST['date_fin'];
                    if ($this->model->create($nom, $description, $date_debut, $date_fin)) {
                        header('Location: manage_campaign.php?success=Campagne créée');
                    } else {
                        header('Location: manage_campaign.php?error=Erreur lors de la création');
                    }
                }
                break;

            case 'edit':
                $id = $_GET['id'];
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $nom = $_POST['nom'];
                    $description = $_POST['description'];
                    $date_debut = $_POST['date_debut'];
                    $date_fin = $_POST['date_fin'];
                    if ($this->model->update($id, $nom, $description, $date_debut, $date_fin)) {
                        header('Location: manage_campaign.php?success=Campagne mise à jour');
                    } else {
                        header('Location: manage_campaign.php?error=Erreur lors de la mise à jour');
                    }
                }
                break;

            case 'delete':
                $id = $_GET['id'];
                if ($this->model->delete($id)) {
                    header('Location: manage_campaign.php?success=Campagne supprimée');
                    exit(); // Ensure script stops after redirect
                } else {
                    header('Location: manage_campaign.php?error=Erreur lors de la suppression');
                    exit(); // Ensure script stops after redirect
                }
                break;

            case 'list':
            default:
                return $this->model->readAll();
        }
    }

    public function getCompagne($id) {
        return $this->model->read($id);
    }
}
?>