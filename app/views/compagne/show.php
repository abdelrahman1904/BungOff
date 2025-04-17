<?php include_once ('../layouts/header.php'); ?>

<div class="container">
    <?php if (isset($_GET['error']) && $_GET['error'] == 'not_found'): ?>
        <div class="alert alert-danger">Campaign not found.</div>
    <?php endif; ?>

    <h1>Campaign Details</h1>
    <?php if (isset($compagne) && is_object($compagne)): ?>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title"><?php echo htmlspecialchars($compagne->titreC); ?></h3>
            </div>
            <div class="card-body">
                <p class="card-text"><?php echo htmlspecialchars($compagne->descriptionC); ?></p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Start Date:</strong> <?php echo htmlspecialchars($compagne->date_debutC); ?>
                    </li>
                    <li class="list-group-item">
                        <strong>End Date:</strong> <?php echo htmlspecialchars($compagne->date_finC); ?>
                    </li>
                    <li class="list-group-item">
                        <strong>ID:</strong> <?php echo htmlspecialchars($compagne->id); ?>
                    </li>
                </ul>
            </div>
            <div class="card-footer">
                <a href="index.php?action=compagnes" class="btn btn-secondary">Back to Campaigns</a>
            </div>
        </div>

        <h2>Promotions in this Campaign</h2>
        <?php if ($promotions && $promotions->rowCount() > 0): ?>
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Discount</th>
                        <th>Promo Code</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($promo = $promotions->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($promo['titreP']); ?></td>
                            <td><?php echo htmlspecialchars($promo['descriptionP']); ?></td>
                            <td><?php echo htmlspecialchars($promo['pourcentage']); ?>%</td>
                            <td><?php echo htmlspecialchars($promo['codePromo']); ?></td>
                            <td><?php echo htmlspecialchars($promo['date_debutP']); ?></td>
                            <td><?php echo htmlspecialchars($promo['date_finP']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No promotions found for this campaign.</div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-danger">Campaign data is not available.</div>
        <a href="index.php?action=compagnes" class="btn btn-secondary">Back to Campaigns</a>
    <?php endif; ?>
</div>

<?php include_once '../layouts/footer.php'; ?>