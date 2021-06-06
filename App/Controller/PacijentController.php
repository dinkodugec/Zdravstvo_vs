<?php

// čitati o Iznimkama https://www.php.net/manual/en/language.exceptions.php
class PacijentController extends AutorizacijaController
{
    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'pacijent'
                        . DIRECTORY_SEPARATOR;

    private $entitet=null;
    private $poruka='';                 


    public function index()
    {
        $this->view->render($this->viewDir . 'index',[
            'entiteti'=>Pacijent::ucitajSve()
        ]);
    }


    public function novo()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $this->noviEntitet();
            return;
        }
        $this->entitet = (object) $_POST;
        try {
            $this->kontrola();
            Pacijent::dodajNovi($this->entitet);
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
            $this->entitet = Pacijent::ucitaj($_GET['sifra']);
            $this->poruka='Promjenite željene podatke';
            $this->promjenaView();
            return;
        }
        $this->entitet = (object) $_POST;
        try {
            $this->kontrolaImePrezime();
            Pacijent::promjeniPostojeci($this->entitet);
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
        Pacijent::obrisiPostojeci($_GET['sifra']);
        header('location: ' . App::config('url') . 'pacijent/index');
       
    }


    private function noviEntitet()
    {
        $this->entitet = new stdClass();
        $this->entitet->ime='';
        $this->entitet->prezime='';
        $this->entitet->oib='';
        $this->entitet->domzdravlja='';
        $this->poruka='Unesite trazene podatke';
        $this->novoView();
    }

    private function promjenaView()
    {
        $this->view->render($this->viewDir . 'promjena',[
            'entitet'=>$this->entitet,
            'poruka'=>$this->poruka

        ]);
    }



    private function novoView()
    {
        $this->view->render($this->viewDir . 'novo',[
            'entitet'=>$this->entitet,
            'poruka'=>$this->poruka

        ]);
    }


    private function kontrola()
    {
        $this->kontrolaImePrezime();
        $this->kontrolaOib();
    }

    private function kontrolaImePrezime()
    {
        $this->kontrolaIme();
        $this->kontrolaPrezime();
    }
  
    private function kontrolaIme()
    {
        if(strlen(trim($this->entitet->ime))==0){
            throw new Exception('Ime obavezno');
        }

        if(strlen(trim($this->entitet->ime))>50){
            throw new Exception('Ime predugačko');
        }
    }

    private function kontrolaPrezime()
    {
        if(strlen(trim($this->entitet->prezime))==0){
            throw new Exception('Prezime obavezno');
        }
    }
    private function kontrolaOib()
    {
        if(!Kontrola::CheckOIB($this->entitet->oib)){
            throw new Exception('OIB nije ispravan');
        }
    }
}
    