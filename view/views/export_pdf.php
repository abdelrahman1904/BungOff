<?php
require_once '../../config.php';
require_once '../../libs/FPDF/fpdf.php';

class PDF extends FPDF {
    private $headerColor = [52, 73, 94]; // Bleu-gris moderne
    private $cardBgColor = [255, 255, 255]; // Blanc
    private $borderColor = [230, 230, 230]; // Gris très clair
    private $activityCount = 0;
    private $maxActivitiesPerPage = 3;

    function Header() {
        $this->SetFillColor(...$this->headerColor);
        $this->Rect(0, 0, 210, 30, 'F');
        // Barre verticale bleue à gauche (style sidebar)
        $this->SetFillColor(31, 40, 51); // Couleur bleu foncé
        $this->Rect(0, 0, 8, 297, 'F'); // 10mm de large, hauteur totale A4

        // Logo (optionnel)
        // Logo avec cadre blanc
if (file_exists('frontoffice/image/bungalow (1).png')) {
    // Coordonnées du cadre
    $x = 30;
    $y = 13;
    $w = 22;
    $h = 22;

    // Cadre blanc avec une bordure grise claire
    $this->Image('frontoffice/image/bungalow (1).png', $x, $y, $w, $h);


    // Image centrée dans le cadre
    $this->Image('frontoffice/image/bungalow (1).png', $x, $y, $w, $h);
}


        // Titre
        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 20, 'Liste des Participants', 0, 1, 'C');
        
        // Date
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, -15, 'Genere le : ' . date('d/m/Y H:i'), 0, 0, 'R');
        $this->Ln(20);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(150, 150, 150);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function CheckPageBreak($heightNeeded) {
        if ($this->GetY() + $heightNeeded > $this->PageBreakTrigger) {
            $this->AddPage();
            $this->activityCount = 0;
            return true;
        }
        return false;
    }

    function ActivityCard($nomActivite, $photoPath, $sessions, $iconPath = 'frontoffice/image/icon.png') {
        if ($this->activityCount >= $this->maxActivitiesPerPage) {
            $this->AddPage();
            $this->activityCount = 0;
        }
    
        $startY = $this->GetY();
        $this->SetFillColor(...$this->cardBgColor);
        $this->SetDrawColor(...$this->borderColor);
        $this->SetLineWidth(0.4);
    
        $this->SetX(35);
    
        // Ajouter l'image de l'activité si elle existe
        if ($photoPath && file_exists($photoPath)) {
            $this->Image($photoPath, 15, $this->GetY(), 25, 25);
            $this->SetX(45);
        }
    
        // Titre de l'activité
        $this->SetFont('Arial', 'B', 14);
        $this->SetTextColor(33, 33, 33);
        $this->Cell(0, 10, utf8_decode($nomActivite), 0, 1);
    
        // Position pour l'icône à droite
        $iconX = 195; // Position horizontale à 195mm (coin droit de la page)
        $iconY = $this->GetY() - 10; // Position verticale un peu au-dessus du titre
    
        // Ajouter l'icône de l'activité dans le coin supérieur droit
        if (file_exists($iconPath)) {
            $this->Image($iconPath, $iconX, $iconY, 10, 10); // Ajustez la taille de l'icône ici
        }
    
        $afterTitleY = max($this->GetY(), $startY + 25);
        $this->SetY($afterTitleY);
        $this->SetDrawColor(...$this->headerColor);
        $this->SetLineWidth(0.5);
        $this->Line(15, $this->GetY(), 195, $this->GetY());
        $this->Ln(5);
    
        foreach ($sessions as $session) {
            $this->SessionBlock($session);
        }
    
        $endY = $this->GetY() + 5;
        $this->RoundedRect(10, $startY, 190, $endY - $startY, 5, 'D');
        $this->Ln(10);
        $this->activityCount++;
    }
    

    function SessionBlock($session) {
        $this->SetX(20);
    
        $this->SetFont('Arial', 'B', 11);
        $this->SetTextColor(52, 152, 219);
    
        // Icône pour le lieu
        if (file_exists('frontoffice/image/location.png')) {
            $this->Image('frontoffice/image/location.png', $this->GetX(), $this->GetY(), 4); // Taille de l'icône
            $this->SetX($this->GetX() + 5); // Espace après l'image
        }
        // Lieu
        $this->Cell(60, 6, utf8_decode($session['lieu']), 0, 0, 'L'); // Réduit la largeur de la cellule
    
        // Icône pour la date
        if (file_exists('frontoffice/image/calendar.png')) {
            $this->Image('frontoffice/image/calendar.png', $this->GetX(), $this->GetY(), 4);
            $this->SetX($this->GetX() + 5); // Espace après l'image
        }
        // Date
        $this->Cell(50, 6, date('d M Y', strtotime($session['date'])), 0, 0, 'L'); // Réduit la largeur de la cellule
    
        // Icône pour l'heure
        if (file_exists('frontoffice/image/clock.png')) {
            $this->Image('frontoffice/image/clock.png', $this->GetX(), $this->GetY(), 4);
            $this->SetX($this->GetX() + 5); // Espace après l'image
        }
        // Heure
        $this->Cell(0, 6, $session['heure_debut'] . ' - ' . $session['heure_fin'], 0, 1, 'L'); // Réduit l'espacement
    
        $this->Ln(3); // Réduit l'espacement entre les sessions
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(50, 50, 50);
    
        foreach ($session['participants'] as $participant) {
            $this->SetX(25);
    
            // Icône utilisateur
            if (file_exists('frontoffice/image/user.png')) {
                $this->Image('frontoffice/image/user.png', $this->GetX(), $this->GetY(), 4); // Taille 4mm
                $this->SetX($this->GetX() + 5); // Espace après l'image
            }
    
            $this->Cell(60, 6, utf8_decode($participant['fullname']), 0, 0);
    
            // Icône email
            if (file_exists('frontoffice/image/mail.png')) {
                $this->Image('frontoffice/image/mail.png', $this->GetX(), $this->GetY(), 4);
                $this->SetX($this->GetX() + 5);
            }
    
            $this->Cell(0, 6, $participant['email'], 0, 1);
        }
    
        $this->Ln(5); // Réduit l'espace après chaque session
    }
    
    
    function RoundedRect($x, $y, $w, $h, $r, $style = '') {
        $k = $this->k;
        $hp = $this->h;
        $op = ($style == 'F') ? 'f' : (($style == 'FD' || $style == 'DF') ? 'B' : 'S');
        $MyArc = 4/3 * (sqrt(2) - 1);

        $this->_out(sprintf('%.2F %.2F m', ($x+$r)*$k, ($hp-$y)*$k));
        $xc = $x+$w-$r; $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k, ($hp-$y)*$k));
        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);

        $xc = $x+$w-$r; $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l', ($x+$w)*$k, ($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x+$r; $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k, ($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);

        $xc = $x+$r; $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $x*$k, ($hp-$yc)*$k));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);

        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k,
            $x3*$this->k, ($h-$y3)*$this->k));
    }
}

// Récupérer ID planification
if (!isset($_GET['id'])) die('ID de planification manquant.');
$idp = $_GET['id'];

try {
    $pdo = config::getConnexion();
    $stmt = $pdo->prepare("
        SELECT 
        i.user_id,
        u.fullname,
        u.email,
        p.*,
        a.titre AS nom_activite,
        a.photo
    FROM inscription i
    JOIN userlist u ON i.user_id = u.id
    JOIN planification p ON i.IDP = p.IDP
    JOIN activite a ON p.nom_activite = a.titre
    ORDER BY a.IDA, p.date, p.heure_debut
    ");
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $grouped = [];
    foreach ($data as $row) {
        $key = $row['nom_activite'] . '_' . $row['date'] . '_' . $row['heure_debut'];
        if (!isset($grouped[$row['nom_activite']])) {
            $grouped[$row['nom_activite']] = [
                'photo' => $row['photo'],
                'sessions' => []
            ];
        }
        if (!isset($grouped[$row['nom_activite']]['sessions'][$key])) {
            $grouped[$row['nom_activite']]['sessions'][$key] = [
                'lieu' => $row['lieu'],
                'date' => $row['date'],
                'heure_debut' => $row['heure_debut'],
                'heure_fin' => $row['heure_fin'],
                'participants' => []
            ];
        }
        $grouped[$row['nom_activite']]['sessions'][$key]['participants'][] = [
            'fullname' => $row['fullname'],
            'email' => $row['email']
        ];
    }

    $pdf = new PDF();
    $pdf->AddPage();

    foreach ($grouped as $nomActivite => $infos) {
        $sessions = array_values($infos['sessions']);
        $pdf->ActivityCard($nomActivite, 'frontoffice/image/' . $infos['photo'], $sessions);
    }

    $pdf->Output('I', 'participants.pdf');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
