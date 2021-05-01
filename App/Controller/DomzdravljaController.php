<?php

class DomzdravljaController extends AutorizacijaController
{
    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'domzdravlja'
                        . DIRECTORY_SEPARATOR;

    private $domzdravlja=null;
    private $poruka='';                 


    public function index()
    {
        $this->view->render($this->viewDir . 'index',[
            'domovizdravlja'=>Domzdravlja::ucitajSve()
        ]);
    }

    public function novo()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->noviDomzdravlja();
            return;
        }

        $this->domzdravlja = (object) $_POST;
        if(!$this->kontrolaNaziv()){return;}
        if(!$this->kontrolaDoktor()){return;}
        if(!$this->kontrolaOrdinacija()){return;}
        Domzdravlja::dodajNovi($this->domzdravlja);
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
        
            $this->dom_zdravlja = Domzdravlja::ucitaj($_GET['sifra']);
            $this->poruka='Promjenite zeljene podatke';
            $this->promjenaView();
            return;
        }  

        $this->domzdravlja = (object) $_POST;
        if(!$this->kontrolaNaziv()){return;}
        if(!$this->kontrolaDoktor()){return;}
        // neću odraditi na promjeni bolnice
        Domzdravlja::promjeniPostojeci($this->domzdravlja);
        $this->index();
    }

    
    public function brisanje()
    {
        if(!isset($_GET['sifra'])){
            $ic = new IndexController();
            $ic->logout();
            return;
        }
        Domzdravlja::obrisiPostojeci($_GET['sifra']);
        $this->index();
        
    }


    private function noviDomzdravlja()
    {
        $this->domzdravlja = new stdClass();
        $this->domzdravlja->doktor='';
        $this->domzdravlja->ordinacija='';
        $this->poruka='Unesite trazene podatke';
        $this->novoView();
    }

    private function novoView()
    {
        $this->view->render($this->viewDir . 'novo',[
            'dom_zdravlja'=>$this->domzdravlja,
            'poruka'=>$this->poruka

        ]);
    }

    private function promjenaView()
    {
        $this->view->render($this->viewDir . 'promjena',[
            'domzdravlja'=>$this->domzdravlja,
            'poruka'=>$this->poruka

        ]);
    }
    
    private function kontrolaNaziv()
    {
        if(strlen(trim($this->domzdravlja->naziv))===0){
            $this->poruka='Naziv obavezno';
            $this->novoView();
            return false;
         }
 
         if(strlen(trim($this->domzdravlja->naziv))>50){
            $this->poruka='Naziv ne može imati više od 50 znakova';
            $this->novoView();
            return false;
         }
         return true;
    }
    
    private function kontrolaDoktor()
    {
        if(strlen(trim($this->domzdravlja->doktor))===0){
            $this->poruka='Doktor obavezno';
            $this->novoView();
            return false;
         }
 
         if(strlen(trim($this->domzdravlja->doktor))>50){
            $this->poruka='Doktor ne može imati više od 50 znakova';
            $this->novoView();
            return false;
         }
         return true;
    }

    private function kontrolaOrdinacija()
    {
        if(strlen(trim($this->domzdravlja->ordinacija))===0){
            $this->poruka='Ordinacija obavezno';
            $this->novoView();
            return false;
         }
 
         if(strlen(trim($this->domzdravlja->ordinacija))>50){
            $this->poruka='Ordinacijane može imati više od 50 znakova';
            $this->novoView();
            return false;
         }
         return true;
    }
}