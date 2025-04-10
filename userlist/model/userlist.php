<?php 

class userlist{

   private $fullname;
   private $username;
   private $email;
   private $pass;
   private $age;
   private $image;

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

    
    function __construct($fullname,$username,$email,$pass,$age,$image){
        $this->fullname = $fullname;
        $this->username = $username;
        $this->email = $email;
        $this->pass = $pass;
        $this->age = $age;
        $this->image = $image;
    }
    


    function affichage(){  
    } 
}
?>


