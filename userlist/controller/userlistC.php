<?php 
require '../config.php';
class userlistC   {
    
    public function adduser($user)
    {
        $sql = "INSERT INTO userlist VALUES (NULL, :fullname, :username, :email, :pass, :age, :image)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                "fullname" => $user->getfullname(),
                "username" => $user->getusername(),
                "email" => $user->getemail(),
                "pass" => $user->getpass(),
                "age" => $user->getage(),
                "image" => $user->getimage(),
            ]);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
  
    public function updateuser($user, $id) {
        $sql = "UPDATE userlist SET fullname = :fullname, username = :username, email = :email, pass = :pass, age = :age, image = :image WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                "id" => $id,
                "fullname" => $user->getfullname(),
                "username" => $user->getusername(),
                "email" => $user->getemail(),
                "pass" => $user->getpass(),
                "age" => $user->getage(),
                "image" => $user->getimage(),
            ]);
        } catch (Exception $e) {
            // Debugging: Output the error message
            echo "Error: " . $e->getMessage();
            exit();
        }
    }
  
    public function deleteuser($id)
    {
        $sql = "DELETE FROM userlist WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                "id" => $id,
            ]);
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function allusers()
    {
        $sql = "SELECT * FROM userlist";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute();
            $service = $query->fetch();
            $res = [];
            for ($x = 0; $service; $x++) {
                $res[$x] = $service;
                $service = $query->fetch();
            }
            return $res;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function findusers($id)
    {
        $sql = "SELECT * FROM userlist WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                "id" => $id,
            ]);
            $service = $query->fetch();
    
            return $service;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    public function findUserByUsernameAndPassword($username, $pass)
    {
        $sql = "SELECT * FROM userlist WHERE username = :username";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['username' => $username]);
            $user = $query->fetch();

            if ($user && $pass === $user['pass']) {
                // Password matches
                return $user;
            } else {
                // Invalid username or password
                return false;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function savePasswordResetToken($email, $token)
    {
        $sql = "INSERT INTO password_resets (email, token) VALUES (:email, :token)";
        $db = config::getConnexion();
        try {
            // Check if the email exists in the userlist table
            $checkSql = "SELECT * FROM userlist WHERE email = :email";
            $checkQuery = $db->prepare($checkSql);
            $checkQuery->execute(['email' => $email]);
            $user = $checkQuery->fetch();

            if ($user) {
                // Email exists, save the token
                $query = $db->prepare($sql);
                $query->execute([
                    'email' => $email,
                    'token' => $token,
                ]);
                return true;
            } else {
                // Email does not exist
                return false;
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function validatePasswordResetToken($token)
    {
        $sql = "SELECT email FROM password_resets WHERE token = :token AND created_at >= NOW() - INTERVAL 1 HOUR";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute(['token' => $token]);
            $result = $query->fetch();
    
            return $result ? $result['email'] : false;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
    
    public function updatePassword($email, $newPassword)
    {
        $sql = "UPDATE userlist SET pass = :pass WHERE email = :email";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'pass' => $newPassword, // Store plain text password
                'email' => $email,
            ]);
    
            // Delete the token after successful password reset
            $deleteSql = "DELETE FROM password_resets WHERE email = :email";
            $deleteQuery = $db->prepare($deleteSql);
            $deleteQuery->execute(['email' => $email]);
    
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>


