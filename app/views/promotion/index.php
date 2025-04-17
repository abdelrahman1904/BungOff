<?php include_once(__DIR__ . '/../layouts/header.php'); ?>

<div class="container">
    <?php if (isset($_GET['error']) && $_GET['error'] == 'not_found'): ?>
        <div class="alert alert-danger">Promotion not found.</div>
    <?php endif; ?>
    
    <h1>Promotions</h1>
    <a href="index.php?action=create_promotion" class="btn btn-primary mb-3">
        <i class="bi bi-plus"></i> Create New Promotion
    </a>
    
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Discount</th>
                <th>Promo Code</th>
                <th>Campaign</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php 
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['idP']); ?></td>
                <td><?php echo htmlspecialchars($row['titreP']); ?></td>
                <td><?php echo htmlspecialchars($row['descriptionP']); ?></td>
                <td><?php echo htmlspecialchars($row['pourcentage']); ?>%</td>
                <td><?php echo htmlspecialchars($row['codePromo']); ?></td>
                <td><?php echo htmlspecialchars($row['category_title']); ?></td>
                <td>
                    
                    <a href="index.php?action=edit_promotion&id=<?php echo $row['idP']; ?>" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="index.php?action=delete_promotion&id=<?php echo $row['idP']; ?>" 
                       class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                        <i class="bi bi-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endwhile; 
    } else { ?>
        <tr>
            <td colspan="7" class="text-center">No promotions found</td>
        </tr>
    <?php } ?>
        </tbody>
    </table>
</div>

<?php include_once(__DIR__ . '/../layouts/footer.php'); ?>