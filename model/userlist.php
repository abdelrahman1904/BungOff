<?php 

class userlist{

   private $fullname;
   private $username;
   private $email;
   private $pass;
   private $age;
   private $image;
   private $role;
   private $is2f;
   private $is2f_secret;

    public $userAttributeCount=6;

    function getfullname(){
        return $this->fullname;
    }
    function getusername(){
        return $this->username;
    }
    function getemail(){
        return $this->email;
    }
    function getpass(){
        return $this->pass;
    }
    function getage(){
        return $this->age;
    }
    function getimage(){
        return $this->image;
    }
    function getrole(){
        return $this->role;
    }
    function getIs2f(){
        return $this->is2f;
    }
    function getIs2f_secret(){
        return $this->is2f_secret;
    }
    
    function __construct($fullname,$username,$email,$pass,$age,$image,$role,$is2f,$is2f_secret){
        $this->fullname = $fullname;
        $this->username = $username;
        $this->email = $email;
        $this->pass = $pass;
        $this->age = $age;
        $this->image = $image;
        $this->role = $role;
        $this->is2f = false;
        $this->is2f_secret = null;
    }
    


    function affichage(){  
    } 
}
?>


