<?php 

class achat{

   private $noma;
   private $cina;
   private $matriculea;
   private $marquea;
   private $typ;
   private $prixa;
   private $datea;
    
    public $userAttributeCount=8;

    function getnoma(){
        return $this->noma;
    }
    function getcina(){
        return $this->cina;
    }
    function getmatriculea(){
        return $this->matriculea;
    }
    function getmarquea(){
        return $this->marquea;
    }
    function gettyp(){
        return $this->typ;
    }
    function getprixa(){
        return $this->prixa;
    }
    function getdatea(){
        return $this->datea;
    }
   

    
    function __construct($noma,$cina,$matriculea,$marquea,$typ,$prixa,$datea){
        $this->noma = $noma;
        $this->cina = $cina;
        $this->matriculea = $matriculea;
        $this->marquea = $marquea;
        $this->typ = $typ;
        $this->prixa = $prixa;
        $this->datea = $datea;
    }
    


    function affichage(){  
    
      // LOCATION DEPENDS ON THE WHERE THE COLLEE AND CALLED FILE ARE NoT DEPENDED ON THE CLASS FILE IS POSITION
     //require_once "showUser.html";
        
    
    } 


   

}
?>


