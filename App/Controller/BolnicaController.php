<?php

class BolnicaController extends AutorizacijaController
{

    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'bolnica'
                        . DIRECTORY_SEPARATOR;
    

     
    private $bolnica=null;
    private $poruka ='';
    



    public function index()
    {
        $this->view->render($this->viewDir . 'index',[
            'bolnice'=>Bolnica::ucitajSve()
        ]);
    }


    public function novo()
    {
       
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->NovaBolnica();
            return;
        }
        $this->bolnica = (object) $_POST;
        if(!$this->kontrolaNaziv()){return;}
        if(!$this->kontrolaRavnatelj()){return;}
        if(!$this->kontrolaOdjel()){return;}
        if(!$this->kontrolaDoktor()){return;}
      
        Bolnica::dodajNovu($this->bolnica);
        $this->index();
        
    }   
    

    public function promjena()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            if(!isset($_GET['sifra'])){
               $ic = new IndexController();
               $ic->logout();
               return;
            }
            $this->bolnica = Bolnica::ucitaj($_GET['sifra']);
            $this->poruka='Promjenite željene podatke';
            $this->promjenaView();
           return;
        }

        $this->bolnica = (object) $_POST;
        if(!$this->kontrolaNaziv()){return;}
        if(!$this->kontrolaOdjel()){return;}
        // odlučujem koje odrađujem kontrole 
        Bolnica::promjeniPostojecu($this->bolnica);
        $this->index();

    }    

    private function novaBolnica()
    {
        $this->bolnica = new stdClass();
        $this->bolnica->naziv='';
        $this->bolnica->ravnatelj='';
        $this->bolnica->odjel='';
        $this->bolnica->doktor='';
        $this->poruka='Unesite željene podatke';
        $this->novoView();
    }


     private function novoView()
    {
        $this->view->render($this->viewDir . 'novo',[
            'bolnica'=>$this->bolnica,
            'poruka'=>$this->poruka
        ]);
    }

    private function promjenaView()
    {
        $this->view->render($this->viewDir . 'promjena',[
            'bolnica'=>$this->bolnica,
            'poruka'=>$this->poruka
        ]);
    }
 

    private function kontrolaNaziv()
    {
        if(strlen(trim($this->bolnica->naziv))===0){
            $this->poruka='Naziv obavezno';
            $this->novoView();
            return false;
         }
 
         if(strlen(trim($this->bolnica->naziv))>50){
            $this->poruka='Naziv ne može imati više od 50 znakova';
            $this->novoView();
            return false;
         }
         return true;
    }


    private function kontrolaRavnatelj()
    {
        if(strlen(trim($this->bolnica->ravnatelj))===0){
            $this->poruka='Ravnatelj obavezno';
            $this->novoView();
            return false;
         }
 
         if(strlen(trim($this->bolnica->ravnatelj))>50){
            $this->poruka='Ravnatelj ne može imati više od 50 znakova';
            $this->novoView();
            return false;
         }
         return true;
    }

    private function kontrolaOdjel()
    {
        if(strlen(trim($this->bolnica->odjel))===0){
            $this->poruka='Odjel obavezno';
            $this->novoView();
            return false;
         }
 
         if(strlen(trim($this->bolnica->odjel))>50){
            $this->poruka='Odjel ne može imati više od 50 znakova';
            $this->novoView();
            return false;
         }
         return true;
    }

    private function kontrolaDoktor()
    {
        if(strlen(trim($this->bolnica->doktor))===0){
            $this->poruka='Doktor obavezno';
            $this->novoView();
            return false;
         }
 
         if(strlen(trim($this->bolnica->doktor))>50){
            $this->poruka='Doktorne može imati više od 50 znakova';
            $this->novoView();
            return false;
         }
         return true;
    }
}    