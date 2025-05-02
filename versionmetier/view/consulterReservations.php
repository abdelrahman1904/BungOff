<?php
require_once '../controller/reservationC.php';
require_once '../controller/bungalowC.php';  // Importation du contrôleur pour récupérer les bungalows
$reservationC = new ReservationC();
$bungalowC = new BungalowC();  // Création de l'objet BungalowC

// Gérer la suppression
if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $id = intval($_GET['supprimer']);
    $reservationC->supprimerReservation($id);
    header("Location: consulterReservations.php?deleted=1");
    exit();
}

$listeReservations = $reservationC->afficherReservations();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Consulter Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="back.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.14/jspdf.plugin.autotable.min.js"></script>
    <style>
        .content h1 {
            color: #0d6efd;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border-radius: 0.25rem;
        }

        .table-responsive {
            background-color: #fff;
            border-radius: 0.25rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .table-dark th {
            background-color: #0d6efd;
            color: white;
            border-color: #0a58ca;
        }

        .table-bordered {
            border: 1px solid #28a745;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #28a745;
        }

        .btn-supprimer {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }

        .btn-supprimer:hover {
            background-color: #0a58ca;
            border-color: #0a58ca;
            color: white;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            color: white;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
            color: white;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
            text-decoration: none;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
            color: white;
        }
    </style>
</head>
<body>

<div class="top-bar">
    <div class="logo">Bung<span class="off">OFF</span></div>
</div>

<div class="sidebar">
    <a href="back.html">Dashboard</a>
    <a href="#">Utilisateurs</a>
    <a href="#">Bungalows</a>
    <a href="#">Activités</a>
    <a href="#">Réservations</a>
</div>

<div class="content">
    <h1>📅 Liste des Réservations</h1>

    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
        <div class="alert alert-success text-center">
            ✅ Réservation supprimée avec succès !
        </div>
    <?php endif; ?>

    <div class="table-responsive mt-4">
        <table class="table table-bordered table-striped" id="reservations-table">
            <thead class="table-dark">
                <tr>
                    <th>Nom du Bungalow</th>
                    <th>Date arrivée</th>
                    <th>Date départ</th>
                    <th>Nombre de personnes</th>
                    <th>Prix total</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($listeReservations)) {
                    foreach ($listeReservations as $reservation) {
                        // Récupérer le bungalow lié à cette réservation
                        $bungalow = $bungalowC->afficherBungalowById($reservation['IDB']);
                        $nom_bungalow = $bungalow['nom'];  // Le nom du bungalow
                        $image_bungalow = $bungalow['image'];  // L'image du bungalow

                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($nom_bungalow) . '</td>';
                        echo '<td>' . htmlspecialchars($reservation['date_arrive']) . '</td>';
                        echo '<td>' . htmlspecialchars($reservation['date_depart']) . '</td>';
                        echo '<td>' . htmlspecialchars($reservation['nbp']) . '</td>';
                        echo '<td>' . htmlspecialchars($reservation['prix_total']) . ' DT</td>';
                        echo '<td><img src="frontoffice/image_video1/' . htmlspecialchars($image_bungalow) . '" alt="Image Bungalow" width="100"></td>';
                        echo '<td>
                            <a href="consulterReservations.php?supprimer=' . $reservation['IDR'] . '"
                                   onclick="return confirm(\'Supprimer cette réservation ?\')">
                                    <button class="btn btn-supprimer btn-sm">
                                        <i class="fas fa-trash-alt"></i> Supprimer
                                    </button>
                                </a>
                              </td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="7" class="text-center">Aucune réservation trouvée</td></tr>';
                } ?>
            </tbody>
        </table>
    </div>

    <div class="button-container mt-4">
        <button class="btn btn-success" id="export-btn">Exporter en PDF</button>
        <a href="newback_bung.html" class="btn btn-secondary">Retour</a>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

<script>
document.getElementById('export-btn').addEventListener('click', async function () {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
        orientation: 'landscape', // Orientation paysage pour mieux accommoder les images
        unit: 'mm'
    });
    
    // Style amélioré
    const primaryColor = [13, 110, 253];
    const secondaryColor = [100, 100, 100];
    const lightColor = [240, 240, 240];
    
    const today = new Date();
    const formattedDate = today.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });

    // En-tête amélioré
    doc.setFillColor(...primaryColor);
    doc.rect(0, 0, doc.internal.pageSize.width, 15, 'F');
    
    doc.setFontSize(12);
    doc.setTextColor(255, 255, 255);
    doc.setFont('helvetica', 'bold');
    doc.text('BungOFF - Liste des Réservations', 10, 10);
    
    doc.setFontSize(8);
    doc.text(`Exporté le : ${formattedDate}`, doc.internal.pageSize.width - 20, 10, {align: 'right'});

    // Titre principal
    doc.setFontSize(14);
    doc.setTextColor(...primaryColor);
    doc.text('Liste des Réservations', 10, 25);

    const table = document.getElementById('reservations-table');
    const rows = table.querySelectorAll('tbody tr');
    const data = [];

    const toDataUrl = async (url) => {
        try {
            const response = await fetch(url);
            const blob = await response.blob();
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onloadend = () => resolve(reader.result);
                reader.readAsDataURL(blob);
            });
        } catch (error) {
            console.error("Erreur de chargement de l'image:", error);
            return null;
        }
    };

    // Préparation des données avec gestion d'erreur
    for (const row of rows) {
        const cols = row.querySelectorAll('td');
        const img = cols[5]?.querySelector('img');
        let imgBase64 = '';
        
        if (img && img.src && !img.src.includes('data:')) {
            imgBase64 = await toDataUrl(img.src);
        } else if (img && img.src) {
            imgBase64 = img.src; // Si déjà en base64
        }

        data.push({
            nom: cols[0]?.innerText || 'N/A',
            arrivee: cols[1]?.innerText || 'N/A',
            depart: cols[2]?.innerText || 'N/A',
            nbp: cols[3]?.innerText || 'N/A',
            prix: cols[4]?.innerText || 'N/A',
            image: imgBase64,
            imgElement: img
        });
    }

    const headers = [
        {header: 'Nom Bungalow', dataKey: 'nom'},
        {header: 'Arrivée', dataKey: 'arrivee'},
        {header: 'Départ', dataKey: 'depart'},
        {header: 'Personnes', dataKey: 'nbp'},
        {header: 'Prix Total (DT)', dataKey: 'prix'},
        {header: 'Image', dataKey: 'image'}
    ];

    // Configuration du tableau
    doc.autoTable({
        columns: headers,
        body: data,
        startY: 30,
        margin: {horizontal: 10},
        styles: { 
            fontSize: 9,
            cellPadding: 3,
            halign: 'center',
            valign: 'middle'
        },
        headStyles: {
            fillColor: primaryColor,
            textColor: 255,
            fontStyle: 'bold'
        },
        alternateRowStyles: {
            fillColor: lightColor
        },
        columnStyles: {
            image: {
                cellWidth: 25,
                minCellHeight: 20
            },
            nom: {
                cellWidth: 'auto',
                halign: 'left'
            },
            prix: {
                halign: 'right'
            }
        },
        didParseCell: function(data) {
            if (data.column.dataKey === 'image' && data.cell.raw) {
                data.cell.text = ''; // Supprime le texte pour les cellules d'image
            }
        },
        willDrawCell: function(data) {
            // Style des cellules de données
            if (data.row.index % 2 !== 0) {
                doc.setFillColor(...lightColor);
                doc.rect(data.cell.x, data.cell.y, data.cell.width, data.cell.height, 'F');
            }
        },
        didDrawCell: function(data) {
            // Dessin des images
            if (data.column.dataKey === 'image' && data.cell.raw) {
                const imgData = data.cell.raw;
                const rowData = data.row.raw;
                const imgEl = rowData.imgElement;

                try {
                    const padding = 2;
                    const maxWidth = data.cell.width - (padding * 2);
                    const maxHeight = data.cell.height - (padding * 2);
                    
                    let imgWidth, imgHeight;
                    const naturalW = imgEl?.naturalWidth || 100;
                    const naturalH = imgEl?.naturalHeight || 100;
                    const ratio = naturalW / naturalH;

                    // Calcul des dimensions pour garder les proportions
                    if (naturalW > naturalH) {
                        imgWidth = Math.min(maxWidth, naturalW);
                        imgHeight = imgWidth / ratio;
                        if (imgHeight > maxHeight) {
                            imgHeight = maxHeight;
                            imgWidth = imgHeight * ratio;
                        }
                    } else {
                        imgHeight = Math.min(maxHeight, naturalH);
                        imgWidth = imgHeight * ratio;
                        if (imgWidth > maxWidth) {
                            imgWidth = maxWidth;
                            imgHeight = imgWidth / ratio;
                        }
                    }

                    const x = data.cell.x + (data.cell.width - imgWidth) / 2;
                    const y = data.cell.y + (data.cell.height - imgHeight) / 2;

                    doc.addImage(imgData, 'JPEG', x, y, imgWidth, imgHeight);
                } catch (error) {
                    console.error("Erreur de dessin de l'image:", error);
                    doc.setTextColor(255, 0, 0);
                    doc.text("Erreur image", data.cell.x + 2, data.cell.y + 10);
                }
            }
        },
        didDrawPage: function(data) {
            // Pied de page
            doc.setFontSize(8);
            doc.setTextColor(...secondaryColor);
            doc.text(
                `Page ${data.pageNumber}`, 
                doc.internal.pageSize.width / 2, 
                doc.internal.pageSize.height - 5,
                {align: 'center'}
            );
        }
    });

    doc.save(`reservations_${formattedDate.replace(/\//g, '-')}.pdf`);
});
</script>


</body>
</html>