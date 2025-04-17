<?php 
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once(__DIR__ . '/../layouts/header.php'); 
?>

<div class="container">
    <?php if (isset($_GET['error']) && $_GET['error'] == 'not_found'): ?>
        <div class="alert alert-danger">Promotion not found.</div>
    <?php endif; ?>

    <h1>Edit Promotion</h1>

    <?php 
    // Debug: Output the promotion data
    // echo '<pre>Promotion Data: '; print_r($promotion); echo '</pre>'; 
    
    if (isset($promotion) && is_object($promotion)): 
    ?>
        <form action="index.php?action=store_promotion" method="POST">
            <input type="hidden" name="idP" value="<?php echo htmlspecialchars($promotion->idP ?? ''); ?>">

            <div class="mb-3">
                <label for="titreP" class="form-label">Title</label>
                <input type="text" class="form-control" id="titreP" name="titreP" required
                    value="<?php echo htmlspecialchars($promotion->titreP ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label for="descriptionP" class="form-label">Description</label>
                <textarea class="form-control" id="descriptionP" name="descriptionP" rows="3"><?php 
                    echo htmlspecialchars($promotion->descriptionP ?? ''); 
                ?></textarea>
            </div>

            <div class="mb-3">
                <label for="pourcentage" class="form-label">Discount Percentage</label>
                <input type="number" class="form-control" id="pourcentage" name="pourcentage" min="1" max="100" required
                    value="<?php echo htmlspecialchars($promotion->pourcentage ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label for="codePromo" class="form-label">Promo Code</label>
                <input type="text" class="form-control" id="codePromo" name="codePromo" required
                    value="<?php echo htmlspecialchars($promotion->codePromo ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label for="date_debutP" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="date_debutP" name="date_debutP" required
                    value="<?php echo htmlspecialchars($promotion->date_debutP ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label for="date_finP" class="form-label">End Date</label>
                <input type="date" class="form-control" id="date_finP" name="date_finP" required
                    value="<?php echo htmlspecialchars($promotion->date_finP ?? ''); ?>">
            </div>

            <div class="mb-3">
                <label for="idC" class="form-label">Campaign</label>
                <select class="form-select" id="idC" name="idC" required>
                    <option value="">Select a campaign</option>
                    <?php
                    try {
                        // Debug: Check what $compagne contains
                        // echo '<pre>Compagne variable: '; var_dump($compagne); echo '</pre>';
                        
                        if (isset($compagne)) {
                            $campaigns = $compagne->fetchAll(PDO::FETCH_ASSOC);
                            
                            // Debug: Output campaigns data
                            // echo '<pre>Campaigns: '; print_r($campaigns); echo '</pre>';
                            
                            if (!empty($campaigns)) {
                                foreach ($campaigns as $campaign) {
                                    echo '<option value="' . htmlspecialchars($campaign['idC'] ?? '') . '"';
                                    echo (isset($promotion->idC) && $campaign['idC'] == $promotion->idC) ? ' selected' : '';
                                    echo '>';
                                    echo htmlspecialchars($campaign['titreC'] ?? '');
                                    echo '</option>';
                                }
                            } else {
                                echo '<option value="" disabled>No campaigns available</option>';
                            }
                        } else {
                            echo '<option value="" disabled>Campaign data not loaded</option>';
                        }
                    } catch (Exception $e) {
                        echo '<option value="" disabled>Error loading campaigns</option>';
                        // Debug: Output the error
                        echo '<!-- Error: ' . htmlspecialchars($e->getMessage()) . ' -->';
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="index.php?action=promotions" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    <?php else: ?>
        <div class="alert alert-warning">No promotion data found to edit.</div>
        <a href="index.php?action=promotions" class="btn btn-secondary">Back to Promotions</a>
    <?php endif; ?>
</div>

<?php include_once(__DIR__ . '/../layouts/footer.php'); ?>