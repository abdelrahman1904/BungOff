<?php include_once(__DIR__ . '/../layouts/header.php'); ?>

<div class="container">
    <?php if (isset($_GET['error']) && $_GET['error'] == 'not_found'): ?>
        <div class="alert alert-danger">Campaign not found.</div>
    <?php endif; ?>
    
    <h1>Campaigns</h1>
    <a href="index.php?action=create_compagne" class="btn btn-primary mb-3">
        <i class="bi bi-plus"></i> Create New Campaign
    </a>
    
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['idC']); ?></td>
                    <td><?php echo htmlspecialchars($row['titreC']); ?></td>
                    <td><?php echo htmlspecialchars($row['descriptionC']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_debutC']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_finC']); ?></td>
                    <td>
                        
                        <a href="index.php?action=edit_compagne&id=<?php echo $row['idC']; ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="index.php?action=delete_compagne&id=<?php echo $row['idC']; ?>" 
                           class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include_once(__DIR__ . '/../layouts/footer.php'); ?>