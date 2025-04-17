<?php include_once(__DIR__ . '/../layouts/header.php'); ?>

<div class="container">
    <?php if (isset($_GET['error']) && $_GET['error'] == 'not_found'): ?>
        <div class="alert alert-danger">Campaign not found.</div>
    <?php endif; ?>

    <h1>Edit Campaign</h1>
    <?php if (isset($compagne) && is_object($compagne)): ?>
        <form action="index.php?action=update_compagne" method="POST">
            <input type="hidden" name="idC" value="<?php echo $compagne->idC; ?>">
            
            <div class="mb-3">
                <label for="titreC" class="form-label">Title</label>
                <input type="text" class="form-control" id="titreC" name="titreC" 
                       value="<?php echo htmlspecialchars($compagne->titreC); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="descriptionC" class="form-label">Description</label>
                <textarea class="form-control" id="descriptionC" name="descriptionC" rows="3">
                    <?php echo htmlspecialchars($compagne->descriptionC); ?>
                </textarea>
            </div>
            
            <div class="mb-3">
                <label for="date_debutC" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="date_debutC" name="date_debutC" 
                       value="<?php echo htmlspecialchars($compagne->date_debutC); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="date_finC" class="form-label">End Date</label>
                <input type="date" class="form-control" id="date_finC" name="date_finC" 
                       value="<?php echo htmlspecialchars($compagne->date_finC); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="id" class="form-label">ID</label>
                <input type="number" class="form-control" id="id" name="id" 
                       value="<?php echo htmlspecialchars($compagne->id); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="index.php?action=compagnes" class="btn btn-secondary">Cancel</a>
        </form>
    <?php else: ?>
        <div class="alert alert-danger">Campaign data is not available.</div>
        <a href="index.php?action=compagnes" class="btn btn-secondary">Back to Campaigns</a>
    <?php endif; ?>
</div>

<?php include_once(__DIR__ . '/../layouts/footer.php'); ?>