<?php
// Logic PHP avant tout contenu HTML
require_once '../controllers/activiteC.php';
require '../vendor/autoload.php';  // Inclure l'autoloader de Composer pour PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_GET['supprimer'])) {
    $id = $_GET['supprimer'];
    $activiteC = new ActiviteC();
    $activiteC->supprimerActivite($id);
    
    // Redirection après suppression
    header("Location: consulter_act.php");
    exit(); // Important pour arrêter l'exécution du script
}

// Exporter en Excel
if (isset($_GET['exporter'])) {
    $activiteC = new ActiviteC();
    $listeActivites = $activiteC->afficherActivites();

    // Créer un nouveau fichier Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Style pour l'en-tête
    $headerStyle = [
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
            'size' => 12
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => '4F81BD']
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => '000000']
            ]
        ]
    ];

    // Style pour les cellules de données
    $dataStyle = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => '000000']
            ]
        ],
        'alignment' => [
            'wrapText' => true,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
        ]
    ];

    // Style conditionnel pour les types d'activité
    $typeStyles = [
        'Aventure' => ['fill' => ['rgb' => 'FFD700']],
        'Détente' => ['fill' => ['rgb' => '98FB98']],
        'Culture' => ['fill' => ['rgb' => 'FFA07A']],
        'Sport' => ['fill' => ['rgb' => 'ADD8E6']]
    ];

    // Définir les largeurs de colonnes
    $sheet->getColumnDimension('A')->setWidth(8);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(40);
    $sheet->getColumnDimension('E')->setWidth(10);
    $sheet->getColumnDimension('F')->setWidth(15);
    $sheet->getColumnDimension('G')->setWidth(30); // Pour la photo
    $sheet->getColumnDimension('H')->setWidth(12);
    $sheet->getColumnDimension('I')->setWidth(8);

    // Ajouter un titre stylisé
    $sheet->mergeCells('A1:I1');
    $sheet->setCellValue('A1', 'LISTE DES ACTIVITÉS - BUNGOFF');
    $sheet->getStyle('A1')->applyFromArray([
        'font' => [
            'bold' => true,
            'size' => 16,
            'color' => ['rgb' => '4F81BD']
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        ]
    ]);

    // Ajouter les en-têtes de colonnes
    $sheet->setCellValue('A3', 'ID')
          ->setCellValue('B3', 'Titre')
          ->setCellValue('C3', 'Guide')
          ->setCellValue('D3', 'Description')
          ->setCellValue('E3', 'Durée')
          ->setCellValue('F3', 'Type')
          ->setCellValue('G3', 'Photo')
          ->setCellValue('H3', 'Prix (DT)')
          ->setCellValue('I3', 'Participants');

    // Appliquer le style à l'en-tête
    $sheet->getStyle('A3:I3')->applyFromArray($headerStyle);

    // Remplir le tableau avec les données
    $sheet->getColumnDimension('I')->setWidth(12);
    $sheet->getStyle('I')->getAlignment()->setWrapText(true);
    $row = 4;  // Début à la ligne 4
    foreach ($listeActivites as $activite) {
        $sheet->setCellValue('A' . $row, $activite['IDA'])
              ->setCellValue('B' . $row, $activite['titre'])
              ->setCellValue('C' . $row, $activite['guide'])
              ->setCellValue('D' . $row, $activite['description'])
              ->setCellValue('E' . $row, $activite['duree'])
              ->setCellValue('F' . $row, $activite['type'])
              ->setCellValue('H' . $row, $activite['prix'])
              ->setCellValue('I' . $row, $activite['NBp']);

        // Ajouter l'image si elle existe
       $photoPath = 'frontoffice/image/' . $activite['photo'];
    if (file_exists($photoPath)) {
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Photo');
        $drawing->setDescription('Photo de l\'activité');
        $drawing->setPath($photoPath);
        $drawing->setHeight(60);
        $drawing->setWidth(80);
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);
        $drawing->setCoordinates('G' . $row);
        $drawing->setWorksheet($sheet);

        // Ajuster la hauteur de la ligne
        $sheet->getRowDimension($row)->setRowHeight(65);

        // Centrer verticalement l'image
        $sheet->getStyle('G' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    } else {
        $sheet->setCellValue('G' . $row, 'Image non disponible');
    }

        // Appliquer le style conditionnel pour le type
        $type = $activite['type'];
        if (isset($typeStyles[$type])) {
            $sheet->getStyle('F' . $row)->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => $typeStyles[$type]['fill']
                ]
            ]);
        }

        // Appliquer le style général aux données
        $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray($dataStyle);

        // Formater le prix avec le symbole DT
        $sheet->getStyle('H' . $row)->getNumberFormat()->setFormatCode('#,##0.00" DT"');

        $row++;
    }

    // Appliquer un style de bandes alternées (zebra)
    $highestRow = $sheet->getHighestRow();
    for ($i = 4; $i <= $highestRow; $i++) {
        $fillColor = $i % 2 == 0 ? 'FFFFFF' : 'E6E6E6';
        $sheet->getStyle('A' . $i . ':I' . $i)->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => $fillColor]
            ]
        ]);
    }

    // Ajouter un pied de page avec la date
    $sheet->mergeCells('A' . ($row + 1) . ':I' . ($row + 1));
    $sheet->setCellValue('A' . ($row + 1), 'Exporté le ' . date('d/m/Y à H:i'));
    $sheet->getStyle('A' . ($row + 1))->applyFromArray([
        'font' => [
            'italic' => true,
            'size' => 10
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
        ]
    ]);

    // Créer un writer pour l'exportation
    $writer = new Xlsx($spreadsheet);
    
    // Définir les headers pour l'exportation
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="activites_bungoff_' . date('Y-m-d') . '.xlsx"');
    header('Cache-Control: max-age=0');
    
    // Enregistrer le fichier Excel dans la sortie PHP
    $writer->save('php://output');
    header("Location: https://docs.google.com/spreadsheets/u/0/");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter Activités - Backoffice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="activity.css">
    <link rel="stylesheet" href="consulter_act.css">
</head>
<body>
    <!-- Barre en haut (identique) -->
    <div class="top-bar">
        <div class="logo d-flex align-items-center gap-2">
            Bung<span class="off">OFF</span>
            <i id="toggleSidebar" class="fas fa-bars" style="cursor: pointer;"></i>
        </div>
        <div class="right-icons">
            <form class="search-form d-inline-flex">
                <input type="text" class="form-control" placeholder="Recherche...">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <div class="login-icon d-inline-flex ms-3">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>

    <!-- Barre latérale (identique) -->
    <div class="sidebar">
        <a href="activity.html"><i class="fas fa-tachometer-alt"></i> dashboard</a>
        <a href="#"><i class="fas fa-user"></i> Utilisateurs</a>
        <a href="#"><i class="fas fa-home"></i> Bungalows</a>
        <a href="new_act.php"><i class="fas fa-campground"></i> Activités</a>
        <a href="#"><i class="fas fa-car"></i> Transport</a>
        <a href="#"><i class="fas fa-credit-card"></i> Paiement</a>
        <a href="#"><i class="fas fa-star"></i> Avis</a>
        <div class="logout">
            <a href="#"><i class="fas fa-sign-out-alt"></i> Se Déconnecter</a>
        </div>
    </div>

    <!-- Contenu principal amélioré -->
    <div class="content">
        <div class="activity-header">
            <a href="new_act.php" class="add-activity-link">
                ⬅ Activité
            </a>
        </div>
        <div class="activities-container">
            <div class="activities-header">
                <h1><i class="fas fa-list-alt"></i> Liste des Activités</h1>
                <div class="table-footer">
                <div class="export-btns">
                    <!-- Lien pour exporter -->
                    <a href="consulter_act.php?exporter=true" class="export-btn"><i class="fas fa-file-excel"></i> exporter en excel</a>
                </div>
            </div>
            </div>

            <div class="table-responsive">
                <table class="activities-table">
                    <thead>
                        <tr>
                            <th>IDP</th>
                            <th>Titre</th>
                            <th>Guide</th>
                            <th>Description</th>
                            <th>Durée</th>
                            <th>Type</th>
                            <th>Photo</th>
                            <th>Prix</th>
                            <th>nbp</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $activiteC = new ActiviteC();
                        $listeActivites = $activiteC->afficherActivites();
                        foreach ($listeActivites as $activite) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($activite['IDA']) . '</td>';
                            echo '<td>' . htmlspecialchars($activite['titre']) . '</td>';
                            echo '<td>' . htmlspecialchars($activite['guide']) . '</td>';
                            echo '<td class="description-cell">' . htmlspecialchars($activite['description']) . '</td>';
                            echo '<td>' . htmlspecialchars($activite['duree']) . '</td>';
                            echo '<td><span class="type-badge ' . strtolower($activite['type']) . '">' . htmlspecialchars($activite['type']) . '</span></td>';
                            echo '<td><img src="frontoffice/image/' . htmlspecialchars($activite['photo']) . '" alt="' . htmlspecialchars($activite['photo']) . '" width="100"></td>';
                            echo '<td>' . htmlspecialchars($activite['prix']) . ' DT</td>';
                            echo '<td>' . htmlspecialchars($activite['NBp']) . '</td>';
                            echo '<td class="actions-cell">
                            <!-- Bouton Modifier -->
                            <a href="modifier_act.php?id=' . htmlspecialchars($activite['IDA']) . '" 
                                class="edit-btn"
                                onclick="return confirmEdit();">
                                <i class="fas fa-edit"></i>
                            </a>
  
                            <!-- Bouton Supprimer -->
                            <a href="consulter_act.php?supprimer=' . htmlspecialchars($activite['IDA']) . '" 
                                class="delete-btn" 
                                onclick="return confirmDelete();">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>';
                            echo '</tr>';
                        }
                    ?>
                    </tbody>
                </table>
            </div>
        
        </div>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Êtes-vous sûr de vouloir supprimer cette activité ?");
        }
        
        function confirmEdit() {
            return confirm("Voulez-vous vraiment modifier cette activité ?");
        }
        document.getElementById('toggleSidebar').addEventListener('click', function () {
            document.querySelector('.sidebar').classList.toggle('collapsed');
            document.querySelector('.content').classList.toggle('collapsed-content');
        });
    </script>
</body>
</html>
