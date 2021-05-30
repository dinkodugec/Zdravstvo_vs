<?php

class LijekController extends AutorizacijaController
{
    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'lijek'
                        . DIRECTORY_SEPARATOR;
    
    private $entitet=null;
    private $poruka='';

    public function index()
    {
        $this->view->render($this->viewDir . 'index',[
            'entiteti'=>Lijek::ucitajSve()
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
            Lijek::dodajNovi($this->entitet);
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
            $this->entitet = Lijek::ucitaj($_GET['sifra']);
            $this->poruka='Promjenite željene podatke';
            $this->promjenaView();
            return;
        }
        $this->entitet = (object) $_POST;
        try {
            $this->kontrolaNaziv();
            Lijek::promjeniPostojeci($this->entitet);
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
        Lijek::obrisiPostojeci($_GET['sifra']);
        header('location: ' . App::config('url') . 'lijek/index');
       
    }







    

    private function noviEntitet()
    {
        $this->entitet = new stdClass();
        $this->entitet->naziv='';
        $this->entitet->bolest='';
        $this->entitet->proizvodac='';
        $this->poruka='Unesite tražene podatke';
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
        $this->kontrolaNaziv();
        $this->kontrolaBolest();
    }


  
    private function kontrolaNaziv()
    {
        if(strlen(trim($this->entitet->naziv))==0){
            throw new Exception('Naziv obavezno');
        }

        if(strlen(trim($this->entitet->naziv))>50){
            throw new Exception('Naziv predugačko');
        }
    }

    private function kontrolaBolest()
    {
        if(strlen(trim($this->entitet->bolest))==0){
            throw new Exception('Bolest obavezno');
        }

        if(strlen(trim($this->entitet->bolest))>50){
            throw new Exception('Bolest predugačko');
        }
    }

}