<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../vendor/autoload.php'; // Correct path if vendor is in the root of your project

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));

        // Save the token and email in the database
        include_once "../../../controller/userlistC.php";
        $userlistC = new userlistC();
        $userExists = $userlistC->savePasswordResetToken($email, $token);

        if ($userExists) {
            // Send reset password email
            $resetLink = "http://localhost/userlist/view/frontoffice/reset_password.php?token=$token";

            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'noreplay.infinitystack@gmail.com'; // Replace with your Gmail address
                $mail->Password = 'zmrq vhsc mvoa tzfs'; //traveltun
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('traveltunisia349@gmail.com', 'Travel Tunisia'); // Replace with your Gmail address
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Password Reset Request';
                $mail->Body = "Click the link below to reset your password:<br><a href='$resetLink'>$resetLink</a>";

                $mail->send();
                $success = 'A password reset link has been sent to your email.';
            } catch (Exception $e) {
                $error = 'Failed to send email. Error: ' . $mail->ErrorInfo; // Display detailed error
            }; // Display detailed error
        } else {
            $error = 'No account found with this email address.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css">
    <title>Forgot Password</title>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h3 class="text-center">Forgot Password</h3>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Enter your email address</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Send Reset Link</button>
                        </div>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="login.php" class="text-decoration-none">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
