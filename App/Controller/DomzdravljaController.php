<?php

class DomzdravljaController extends AutorizacijaController
{
    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'dom_zdravlja'
                        . DIRECTORY_SEPARATOR;

    private $dom_zdravlja=null;
    private $poruka='';                 


    public function index()
    {
        $this->view->render($this->viewDir . 'index',[
            'domovizdravlja'=>Dom_Zdravlja::ucitajSve()
        ]);
    }

    public function novo()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->noviDom_Zdravlja();
            return;
        }

        $this->dom_zdravlja = (object) $_POST;
        if(!$this->kontrolaNaziv()){return;}
        if(!$this->kontrolaDoktor()){return;}
        if(!$this->kontrolaOrdinacija()){return;}
        Dom_Zdravlja::dodajNovi($this->dom_zdravlja);
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
        
            $this->dom_zdravlja = Dom_Zdravlja::ucitaj($_GET['sifra']);
            $this->poruka='Promjenite zeljene podatke';
            $this->promjenaView();
            return;
        }  

        $this->dom_zdravlja = (object) $_POST;
        if(!$this->kontrolaNaziv()){return;}
        if(!$this->kontrolaDoktor()){return;}
        // neću odraditi na promjeni bolnice
        Dom_Zdravlja::promjeniPostojeci($this->dom_zdravlja);
        $this->index();
    }

    
    public function brisanje()
    {
        if(!isset($_GET['sifra'])){
            $ic = new IndexController();
            $ic->logout();
            return;
        }
        Dom_Zdravlja::obrisiPostojeci($_GET['sifra']);
        $this->index();
        
    }


    private function noviDom_Zdravlja()
    {
        $this->dom_zdravlja = new stdClass();
        $this->dom_zdravlja->doktor='';
        $this->dom_zdravlja->ordinacija='';
        $this->poruka='Unesite trazene podatke';
        $this->novoView();
    }

    private function novoView()
    {
        $this->view->render($this->viewDir . 'novo',[
            'dom_zdravlja'=>$this->dom_zdravlja,
            'poruka'=>$this->poruka

        ]);
    }

    private function promjenaView()
    {
        $this->view->render($this->viewDir . 'promjena',[
            'dom_zdravlja'=>$dom_zdravlja,
            'poruka'=>$poruka

        ]);
    }
    
    private function kontrolaNaziv()
    {
        if(strlen(trim($this->dom_zdravlja->naziv))===0){
            $this->poruka='Naziv obavezno';
            $this->novoView();
            return false;
         }
 
         if(strlen(trim($this->dom_zdravlja->naziv))>50){
            $this->poruka='Naziv ne može imati više od 50 znakova';
            $this->novoView();
            return false;
         }
         return true;
    }
    
    private function kontrolaDoktor()
    {
        if(strlen(trim($this->dom_zdravlja->doktor))===0){
            $this->poruka='Doktor obavezno';
            $this->novoView();
            return false;
         }
 
         if(strlen(trim($this->dom_zdravlja->doktor))>50){
            $this->poruka='Doktor ne može imati više od 50 znakova';
            $this->novoView();
            return false;
         }
         return true;
    }

    private function kontrolaOrdinacija()
    {
        if(strlen(trim($this->dom_zdravlja->ordinacija))===0){
            $this->poruka='Ordinacija obavezno';
            $this->novoView();
            return false;
         }
 
         if(strlen(trim($this->dom_zdravlja->ordinacija))>50){
            $this->poruka='Ordinacijane može imati više od 50 znakova';
            $this->novoView();
            return false;
         }
         return true;
    }
}