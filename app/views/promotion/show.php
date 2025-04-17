<?php include_once ('../layouts/header.php'); ?>

<div class="container">
    <?php if (isset($_GET['error']) && $_GET['error'] == 'not_found'): ?>
        <div class="alert alert-danger">Promotion not found.</div>
    <?php endif; ?>

    <h1>Promotion Details</h1>
    <?php if (isset($promotion) && is_object($promotion)): ?>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title"><?php echo htmlspecialchars($promotion->titreP); ?></h3>
            </div>
            <div class="card-body">
                <p class="card-text"><?php echo htmlspecialchars($promotion->descriptionP); ?></p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Discount:</strong> <?php echo htmlspecialchars($promotion->pourcentage); ?>%
                    </li>
                    <li class="list-group-item">
                        <strong>Promo Code:</strong> <?php echo htmlspecialchars($promotion->codePromo); ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Start Date:</strong> <?php echo htmlspecialchars($promotion->date_debutP); ?>
                    </li>
                    <li class="list-group-item">
                        <strong>End Date:</strong> <?php echo htmlspecialchars($promotion->date_finP); ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Campaign:</strong> <?php echo htmlspecialchars($promotion->category_title); ?>
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="index.php?action=promotions" class="btn btn-secondary">Back to Promotions</a>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">Promotion data is not available.</div>
        <a href="index.php?action=promotions" class="btn btn-secondary">Back to Promotions</a>
    <?php endif; ?>
</div>

<?php include_once '../layouts/footer.php'; ?>