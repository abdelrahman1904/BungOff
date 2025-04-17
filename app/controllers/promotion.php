<?php
class PromotionController {
    private $db;
    private $promotion;
    
    public function __construct() {
        $database = new config();
        $this->db = $database->getConnexion();
        $this->promotion = new Promotion($this->db);
    }
    
    public function index() {
        $stmt = $this->promotion->readAll();
        include_once 'views/promotion/index.php';
    }
    
    public function create() {
        $compagne = new Compagne($this->db);
        $compagne = $compagne->readAll();
        include_once 'views/promotion/create.php';
    }
    
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->promotion->titreP = $_POST['titreP'];
            $this->promotion->descriptionP = $_POST['descriptionP'];
            $this->promotion->pourcentage = $_POST['pourcentage'];
            $this->promotion->codePromo = $_POST['codePromo'];
            $this->promotion->date_debutP = $_POST['date_debutP'];
            $this->promotion->date_finP = $_POST['date_finP'];
            $this->promotion->idC = $_POST['idC'];
            
            if($this->promotion->create()) {
                header("Location: index.php?action=promotions");
                exit();
            } else {
                echo "Promotion could not be created.";
            }
        }
    }
    
    public function edit($id) {
        $this->promotion->idP = $id;
        $promotion = $this->promotion->readOne(); // Now gets the promotion object
        
        if(!$promotion) {
            header("Location: index.php?action=promotions&error=not_found");
            exit();
        }
        
        $compagne = new Compagne($this->db);
        $compagnes = $compagne->readAll();
        
        include_once 'views/promotion/edit.php';
    }
    
    public function update() {
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->promotion->idP = $_POST['idP'];
            $this->promotion->titreP = $_POST['titreP'];
            $this->promotion->descriptionP = $_POST['descriptionP'];
            $this->promotion->pourcentage = $_POST['pourcentage'];
            $this->promotion->codePromo = $_POST['codePromo'];
            $this->promotion->date_debutP = $_POST['date_debutP'];
            $this->promotion->date_finP = $_POST['date_finP'];
            $this->promotion->idC = $_POST['idC'];
            
            if($this->promotion->update()) {
                header("Location: index.php?action=promotions");
                exit();
            } else {
                echo "Promotion could not be updated.";
            }
        }
    }
    
    public function delete($id) {
        $this->promotion->idP = $id;
        
        if($this->promotion->delete()) {
            header("Location: index.php?action=promotions");
            exit();
        } else {
            echo "Promotion could not be deleted.";
        }
    }
    
    public function show($id) {
        $this->promotion->idP = $id;
        if(!$this->promotion->readOne()) {
            header("Location: index.php?action=promotions&error=not_found");
            exit();
        }
        
        include_once 'views/promotion/show.php';
    }
}
?>