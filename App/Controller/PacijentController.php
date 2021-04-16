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
            $this->kontrolaIme();
            $this->kontrolaPrezime();
        } catch (Exception $e) {
            $this->poruka=$e->getMessage();
            $this->novoView();
            return;
        }
        
        Pacijent::dodajNovi($this->entitet);
        $this->index();
        
    }


    private function noviEntitet()
    {
        $this->entitet = new stdClass();
        $this->entitet->ime='';
        $this->entitet->prezime='';
        $this->entitet->oib='';
        $this->entitet->dom_zdravlja='';
        $this->poruka='Unesite trazene podatke';
        $this->novoView();
    }


    private function novoView()
    {
        this->view->render($this->viewDir . 'novo',[
            'entitet'=>$this->entitet,
            'poruka'=>$this->poruka

        ]);
    }


    private function kontrolaIme()
    {
        if(strlen(trim($this->entitet->ime))==0){
            throw new Exception('Ime obavezno');
        }
    }

    private function kontrolaPrezime()
    {
        if(strlen(trim($this->entitet->prezime))==0){
            throw new Exception('Prezime obavezno');
        }
    }
}
    