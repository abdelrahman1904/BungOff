<?php
class CompagneController {
    private $db;
    private $compagne;
    
    public function __construct() {
        $database = new config();
        $this->db = $database->getConnexion();
        $this->compagne = new Compagne($this->db);
    }
    
    public function index() {
        $stmt = $this->compagne->readAll();
        include_once 'views/compagne/index.php';
    }
    
    public function create() {
        include_once 'views/compagne/create.php';
    }
    
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->compagne->titreC = $_POST['titreC'];
            $this->compagne->descriptionC = $_POST['descriptionC'];
            $this->compagne->date_debutC = $_POST['date_debutC'];
            $this->compagne->date_finC = $_POST['date_finC'];
            $this->compagne->id = $_POST['id'];
            
            if($this->compagne->create()) {
                header("Location: index.php?action=compagnes");
                exit();
            } else {
                echo "Compagne could not be created.";
            }
        }
    }
    
    public function edit($id) {
        $this->compagne->idC = $id;
        if(!$this->compagne->readOne()) {
            header("Location: index.php?action=compagnes&error=not_found");
            exit();
        }
        
        include_once 'views/compagne/edit.php';
    }
    
    public function update() {
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $this->compagne->idC = $_POST['idC'];
            $this->compagne->titreC = $_POST['titreC'];
            $this->compagne->descriptionC = $_POST['descriptionC'];
            $this->compagne->date_debutC = $_POST['date_debutC'];
            $this->compagne->date_finC = $_POST['date_finC'];
            $this->compagne->id = $_POST['id'];
            
            if($this->compagne->update()) {
                header("Location: index.php?action=compagnes");
                exit();
            } else {
                echo "Compagne could not be updated.";
            }
        }
    }
    
    public function delete($id) {
        $this->compagne->idC = $id;
        
        if($this->compagne->delete()) {
            header("Location: index.php?action=compagnes");
            exit();
        } else {
            echo "Compagne could not be deleted.";
        }
    }
    
    public function show($id) {
        $this->compagne->idC = $id;
        if(!$this->compagne->readOne()) {
            header("Location: index.php?action=compagnes&error=not_found");
            exit();
        }
        
        $promotion = new Promotion($this->db);
        $promotions = $promotion->getPromotionsByCompagne($id);
        
        include_once 'views/compagne/show.php';
    }
}
?>