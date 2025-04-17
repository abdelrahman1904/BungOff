<?php include_once(__DIR__ . '/../views/layouts/header.php'); ?>

<div class="container">
    <h1 class="mt-5">Welcome to Campaign Management System</h1>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Campaigns</h5>
                    <p class="card-text">Manage your marketing campaigns here.</p>
                    <a href="index.php?action=compagnes" class="btn btn-primary">Go to Campaigns</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Promotions</h5>
                    <p class="card-text">Manage your promotions and discounts here.</p>
                    <a href="index.php?action=promotions" class="btn btn-primary">Go to Promotions</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once(__DIR__ . '/../views/layouts/footer.php'); ?>