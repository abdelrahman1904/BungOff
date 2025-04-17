<?php
// Database configuration
require_once ('../config.php');

// Include model classes
require_once 'models/compagne.php';
require_once 'models/promotion.php';

// Include controller classes
require_once 'controllers/compagneC.php';
require_once 'controllers/promotion.php';

// Create database connection
$database = new config();
$db = $database->getConnexion();

// Determine action from URL
$action = isset($_GET['action']) ? $_GET['action'] : 'home';

// Initialize controllers
$compagneController = new CompagneController();
$promotionController = new PromotionController();

// Route actions
switch ($action) {
    case 'compagnes':
        $compagneController->index();
        break;
    case 'create_compagne':
        $compagneController->create();
        break;
    case 'store_compagne':
        $compagneController->store();
        break;
    case 'edit_compagne':
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
        $compagneController->edit($id);
        break;
    case 'update_compagne':
        $compagneController->update();
        break;
    case 'delete_compagne':
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
        $compagneController->delete($id);
        break;
    case 'show_compagne':
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
        $compagneController->show($id);
        break;
    case 'promotions':
        $promotionController->index();
        break;
    case 'create_promotion':
        $promotionController->create();
        break;
    case 'store_promotion':
        $promotionController->store();
        break;
    case 'edit_promotion':
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
        $promotionController->edit($id);
        break;
    case 'update_promotion':
        $promotionController->update();
        break;
    case 'delete_promotion':
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
        $promotionController->delete($id);
        break;
    case 'show_promotion':
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: missing ID.');
        $promotionController->show($id);
        break;
    default:
        include_once 'views/home.php';
        break;
}
?>