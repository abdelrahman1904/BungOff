<?php 
    // 
    include_once "../../model/userlist.php";
    include_once "../../controller/userlistC.php";
    
    if(isset($_POST['fullname'])) {
        // Handle image upload
        $imageName = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $originalName = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
            $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $randomName = uniqid('img_', true) . '.' . $extension; // Generate a unique random name
            $uploadDir = 'user_images/';
            $uploadPath = $uploadDir . $randomName;

            // Ensure the upload directory exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            // Move the uploaded file to the target directory
            if (move_uploaded_file($imageTmpPath, $uploadPath)) {
                $imageName = $randomName; // Save the random name if upload succeeds
            }
        }

        // Use plain text password
        $user1 = new userlist($_POST['fullname'], $_POST['username'], $_POST['email'], $_POST['pass'], $_POST['age'], $imageName, "user", false, null);
        $r = new userlistC();
        
        $r->adduser($user1);
        header('Location:login.php');
    } else {
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <title>Registration Form</title>
</head>
<body>
<video class="full-width-video" autoplay muted loop>
    <source src="image_video/Lets Discover Tunisia.mp4" type="video/mp4">
    Votre navigateur ne supporte pas la lecture de vidéos.
</video>
<section class="pdt-120 pdb-120 mt-5">
    <div class="container" style="margin-top: 100px; position: relative; z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center">
                            <img src="image_video/projet web bungoff.png" alt="Logo" class="img-fluid rounded-circle">
                        </div>
                        <form action="registration.php" method="post" id="myForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="fullname" id="fullname" placeholder="Enter your full name">
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Enter your username">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email">
                            </div>
                            <div class="mb-3">
                                <label for="age" class="form-label">Age</label>
                                <input type="number" class="form-control" name="age" id="age" placeholder="Enter your age">
                            </div>
                            <div class="mb-3">
                                <label for="pass" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="pass" id="pass" placeholder="Enter your password">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Profile Image</label>
                                <input type="file" class="form-control" name="image" id="image" accept="image/*">
                            </div>
                            <div class="d-grid">
                                <input type="submit" class="btn btn-primary btn-block" value="Register" name="submit">
                            </div>
                        </form>
                        <div class="mt-4 text-center">
                            <p>Already Registered? <a href="login.php" class="text-decoration-none">Login Here</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    .full-width-video {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: -1;
    }
</style>
<script>
    document.getElementById('myForm').addEventListener('submit', function (event) {
        const fullname = document.getElementById('fullname').value.trim();
        const username = document.getElementById('username').value.trim();
        const email = document.getElementById('email').value.trim();
        const age = document.getElementById('age').value.trim();
        const password = document.getElementById('pass').value.trim();
        let errorMessage = '';

        if (fullname === '') {
            errorMessage = 'Full name is required.';
        } else if (username === '') {
            errorMessage = 'Username is required.';
        } else if (email === '') {
            errorMessage = 'Email is required.';
        } else if (!/^\S+@\S+\.\S+$/.test(email)) {
            errorMessage = 'Invalid email format.';
        } else if (age === '' || isNaN(age) || age <= 0) {
            errorMessage = 'Please enter a valid age.';
        } else if (password === '') {
            errorMessage = 'Password is required.';
        } else if (password.length < 6) {
            errorMessage = 'Password must be at least 6 characters long.';
        }

        if (errorMessage) {
            event.preventDefault(); // Prevent form submission
            alert(errorMessage); // Display error message
        }
    });

    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('pass');
        const icon = this.querySelector('i');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        }
    });
</script>
</body>
</html>
<?php } ?>