<?php 

class location{

   private $nomf;
   private $cinf;
   private $matricule;
   private $marque;
   private $prix;
   private $dated;
   private $datef;
    
    public $userAttributeCount=6;

    function getnomf(){
        return $this->nomf;
    }
    function getcinf(){
        return $this->cinf;
    }
    function getmatricule(){
        return $this->matricule;
    }
    function getmarque(){
        return $this->marque;
    }
    function getprix(){
        return $this->prix;
    }
    function getdated(){
        return $this->dated;
    }
    function getdatef(){
        return $this->datef;
    }
   

    
    function __construct($nomf,$cinf,$matricule,$marque,$prix,$dated,$datef){
        $this->nomf = $nomf;
        $this->cinf = $cinf;
        $this->matricule = $matricule;
        $this->marque = $marque;
        $this->prix = $prix;
        $this->dated = $dated;
        $this->datef = $datef;
    }
    


    function affichage(){  
    
      // LOCATION DEPENDS ON THE WHERE THE COLLEE AND CALLED FILE ARE NoT DEPENDED ON THE CLASS FILE IS POSITION
     //require_once "showUser.html";
        
    
    } 


   

}
?>


