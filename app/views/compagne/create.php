<?php include_once(__DIR__ . '/../layouts/header.php'); ?>

<div class="container">
    <h1>Create New Campaign</h1>
    <form id="campaignForm" action="index.php?action=store_compagne" method="POST">
        <div class="mb-3">
            <label for="titreC" class="form-label">Title</label>
            <input type="text" class="form-control" id="titreC" name="titreC" required>
        </div>
        <div class="mb-3">
            <label for="descriptionC" class="form-label">Description</label>
            <textarea class="form-control" id="descriptionC" name="descriptionC" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="date_debutC" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="date_debutC" name="date_debutC" required>
        </div>
        <div class="mb-3">
            <label for="date_finC" class="form-label">End Date</label>
            <input type="date" class="form-control" id="date_finC" name="date_finC" required>
        </div>
        <div class="mb-3">
            <label for="id" class="form-label">ID</label>
            <input type="number" class="form-control" id="id" name="id" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="index.php?action=compagnes" class="btn btn-secondary">Cancel</a>
    </form>
    <!-- Inclusion du script externe -->
<script src="/assets/js/validation.js"></script>
</div>



<?php include_once(__DIR__ . '/../layouts/footer.php'); ?>

