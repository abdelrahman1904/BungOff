<?php
require('../libs/fpdf.php'); 
require_once '../models/config.php'; 

class PDF extends FPDF {
    // Header with modern design
    function Header() {
        // Modern minimalist header with accent color
        $this->SetFillColor(240, 240, 240); // Light gray background
        $this->Rect(0, 0, $this->w, 30, 'F');
        
        // Left accent bar
        $this->SetFillColor(30, 144, 255); // Dodger blue accent
        $this->Rect(0, 0, 10, 30, 'F');
        
        // Logo positioned in top-left corner
        $this->Image('frontoffice/image/maison.jpg', 15, 8, 25);
        // Add "BungOFF" next to the logo
        $this->SetXY(45, 12); // Position near the image
        $this->SetFont('Helvetica', 'B', 16);
        $this->SetTextColor(30, 144, 255); // Same Dodger Blue color
        $this->Cell(40, 10, 'BungOFF', 0, 0, 'L');
        // Title with modern typography
        $this->SetY(10);
        $this->SetFont('Helvetica', 'B', 20);
        $this->SetTextColor(40, 40, 40); // Dark gray text
        $this->Cell(0, 8, 'LISTE DES PARTICIPANTS', 0, 1, 'C');
        
        // Subtitle with date
        $this->SetFont('Helvetica', 'I', 10);
        $this->SetTextColor(120, 120, 120); // Medium gray
        $this->Cell(0, 5, 'Rapport des inscriptions aux activites', 0, 1, 'C');
        
        // Date with modern styling
        $this->SetFont('Helvetica', '', 9);
        $this->SetTextColor(150, 150, 150);
        $this->Cell(0, 5, 'Genere le: ' . date('d/m/Y à H:i'), 0, 1, 'R');
        
        $this->Ln(10);
        
        // Decorative line separator
        $this->SetDrawColor(200, 200, 200);
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY(), $this->w-10, $this->GetY());
        $this->Ln(8);
    }
    
    // Footer with minimalist design
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 8);
        $this->SetTextColor(150, 150, 150);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
        
        // Footer line
        $this->SetDrawColor(200, 200, 200);
        $this->Line(10, $this->GetY()-2, $this->w-10, $this->GetY()-2);
    }
    
    // Modern table design
    function ImprovedTable($header, $data, $widths) {
        // Table header styling
        $this->SetFillColor(30, 144, 255); // Dodger blue
        $this->SetTextColor(255);
        $this->SetDrawColor(220, 220, 220);
        $this->SetLineWidth(0.3);
        $this->SetFont('Helvetica', 'B', 11);
        
        // Header row
        for($i = 0; $i < count($header); $i++) {
            $this->Cell($widths[$i], 10, utf8_decode($header[$i]), 1, 0, 'C', true);
        }
        $this->Ln();
        
        // Table body styling
        $this->SetFillColor(248, 248, 248); // Very light gray
        $this->SetTextColor(60, 60, 60);
        $this->SetFont('Helvetica', '', 10);
        
        // Data rows with alternating colors
        $fill = false;
        foreach($data as $row) {
            // Page break check
            if($this->GetY() > $this->PageBreakTrigger - 15) {
                $this->AddPage();
                $this->ImprovedTable($header, array($row), $widths);
                continue;
            }
            
            $this->Cell($widths[0], 8, $row['user_id'], 'LR', 0, 'C', $fill);
            $this->Cell($widths[1], 8, utf8_decode($row['fullname']), 'LR', 0, 'L', $fill);
            $this->Cell($widths[2], 8, utf8_decode($row['email']), 'LR', 0, 'L', $fill);
            $this->Cell($widths[3], 8, utf8_decode($row['nom_activite']), 'LR', 0, 'L', $fill);
            $this->Cell($widths[4], 8, utf8_decode($row['lieu']), 'LR', 0, 'L', $fill);
            $this->Cell($widths[5], 8, $row['date'], 'LR', 0, 'C', $fill);
            $this->Cell($widths[6], 8, $row['heure_debut'], 'LR', 0, 'C', $fill);
            $this->Cell($widths[7], 8, $row['heure_fin'], 'LR', 0, 'C', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Table bottom border
        $this->Cell(array_sum($widths), 0, '', 'T');
    }
}

try {
    $pdo = config::getConnexion();
    $query = $pdo->prepare("
    SELECT 
        i.user_id,
        u.fullname,
        u.email,
        p.nom_activite,
        p.lieu,
        p.date,
        p.heure_debut,
        p.heure_fin
    FROM inscription i
    JOIN userlist u ON i.user_id = u.id
    JOIN planification p ON i.IDP = p.IDP
    ORDER BY i.user_id ASC
");

    $query->execute();
    $participants = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Erreur : ' . $e->getMessage());
}

// Create PDF
$pdf = new PDF('L');
$pdf->AliasNbPages();
$pdf->AddPage();

// Column headers and widths
$header = ['ID', 'Nom', 'Email', 'Activité', 'Lieu', 'Date', 'H_début', 'H_fin'];
$widths = [15, 40, 60, 40, 30, 25, 20, 20];

// Print table
$pdf->ImprovedTable($header, $participants, $widths);

// Modern summary section
$pdf->Ln(12);
$pdf->SetFont('Helvetica', 'B', 11);
$pdf->SetTextColor(30, 144, 255);
$pdf->Cell(40, 8, 'Resume:', 0, 0);
$pdf->SetFont('Helvetica', '', 10);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell(0, 8, count($participants) . ' participants inscrits', 0, 1);

// Output
$pdf->Output('D', 'Liste_Participants_' . date('Y-m-d') . '.pdf');
exit();
?>