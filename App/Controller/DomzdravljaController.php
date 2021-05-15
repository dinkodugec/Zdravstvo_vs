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

        try {
            $this->kontrola();
            Domzdravlja::dodajNovi($this->domzdravlja);
            $this->index();
        } catch (Exception $e) {
            $this->poruka=$e->getMessage();
            $this->novoView();
        }       
        
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
        try {
            $this->kontrola();
            Domzdravlja::promjeniPostojeci($this->domzdravlja);
            $this->index();
        } catch (Exception $e) {
            $this->poruka=$e->getMessage();
            $this->promjenaView();
        }       
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
        $this->domzdravlja->naziv='';
        $this->domzdravlja->doktor='';
        $this->domzdravlja->doktor='';
        $this->domzdravlja->ordinacija='';
        $this->poruka='Unesite trazene podatke';
        $this->novoView();
    }

    private function promjenaView()
    {
        $this->view->render($this->viewDir . 'promjena',[
            'domzdravlja'=>$this->domzdravlja,
            'poruka'=>$this->poruka

        ]);
    }


    private function novoView()
    {
        $this->view->render($this->viewDir . 'novo',[
            'domzdravlja'=>$this->domzdravlja,
            'poruka'=>$this->poruka

        ]);
    }

   
    
    private function kontrola()
    {
        $this->kontrolaNaziv();
        $this->kontrolaDoktor();
    }

    private function kontrolaNaziv()
    {
        if(strlen(trim($this->domzdravlja->naziv))==0){
            throw new Exception('Naziv obavezno');
        }

        if(strlen(trim($this->domzdravlja->naziv))>50){
            throw new Exception('Naziv predugačak');
        }
    }
  
    private function kontrolaDoktor()
    {
        if(strlen(trim($this->doktor->naziv))==0){
            throw new Exception('Doktor obavezno');
        }

        if(strlen(trim($this->doktor->naziv))>50){
            throw new Exception('Doktor predugačak');
        }
    }

}