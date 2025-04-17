<?php include_once(__DIR__ . '/../layouts/header.php'); ?>

<div class="container">
    <h1>Create New Promotion</h1>
    <form action="index.php?action=store_promotion" method="POST">
        <div class="mb-3">
            <label for="titreP" class="form-label">Title</label>
            <input type="text" class="form-control" id="titreP" name="titreP" required>
        </div>
        <div class="mb-3">
            <label for="descriptionP" class="form-label">Description</label>
            <textarea class="form-control" id="descriptionP" name="descriptionP" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="pourcentage" class="form-label">Discount Percentage</label>
            <input type="number" class="form-control" id="pourcentage" name="pourcentage" min="1" max="100" required>
        </div>
        <div class="mb-3">
            <label for="codePromo" class="form-label">Promo Code</label>
            <input type="text" class="form-control" id="codePromo" name="codePromo" required>
        </div>
        <div class="mb-3">
            <label for="date_debutP" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="date_debutP" name="date_debutP" required>
        </div>
        <div class="mb-3">
            <label for="date_finP" class="form-label">End Date</label>
            <input type="date" class="form-control" id="date_finP" name="date_finP" required>
        </div>
        <div class="mb-3">
            <label for="idC" class="form-label">Campaign</label>
            <select class="form-select" id="idC" name="idC" required>
                <option value="">Select a campaign</option>
                <?php
                // Fetch campaigns in a separate variable before the loop
                $campaigns = $compagne->fetchAll(PDO::FETCH_ASSOC);
                foreach ($campaigns as $campaign): ?>
                <option value="<?php echo $campaign['idC']; ?>">
                    <?php echo htmlspecialchars($campaign['titreC']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="index.php?action=promotions" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php include_once(__DIR__ . '/../layouts/footer.php'); ?>