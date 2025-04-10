<?php
include_once "../controller/userlistC.php";

$error = '';
$success = '';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Validate the token
    $userlistC = new userlistC();
    $email = $userlistC->validatePasswordResetToken($token);

    if ($email) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
            $newPassword = $_POST['new_password'];

            // Update the password in the database
            $updated = $userlistC->updatePassword($email, $newPassword);

            if ($updated) {
                $success = 'Your password has been reset successfully. You can now <a href="login.php">login</a>.';
            } else {
                $error = 'Failed to reset your password. Please try again.';
            }
        }
    } else {
        $error = 'Invalid or expired token.';
    }
} else {
    $error = 'No token provided.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <title>Reset Password</title>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h3 class="text-center">Reset Password</h3>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php else: ?>
                        <form method="post">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" name="new_password" id="new_password" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
