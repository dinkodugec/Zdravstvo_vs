<?php

class BolnicaController extends AutorizacijaController
{

    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'bolnica'
                        . DIRECTORY_SEPARATOR;
    

     


    public function index()
    {
        $this->view->render($this->viewDir . 'index',[
            'bolnice'=>Bolnica::ucitajSve()
        ]);
    }


    public function novo()
    {
       
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $bolnica = new stdClass();
            $bolnica->naziv='';
            $bolnica->ravnatelj='';
            $bolnica->odjel='';
            $bolnica->doktor='';
            
            $this->novoView($bolnica, 'Popunite sve podatke');
            return;
        }
       
        $bolnica = (object) $_POST;
       
        //naziv
        if(strlen(trim($bolnica->naziv))===0){
            $this->novoView($bolnica, 'Naziv obvezno');
        return;
        }

        if(strlen(trim($bolnica->naziv))>50){
            $this->novoView($bolnica, 'Naziv ne moze imati više od 50 znakova');
            return;
        }

        
        //ravnatelj
        if(strlen(trim($bolnica->ravnatelj))===0){
            $this->novoView($bolnica, 'Ravnatelj obvezno');
        return;
        }

        if(strlen(trim($bolnica->ravnatelj))>50){
            $this->novoView($bolnica, 'Ravnatelj ne moze imati više od 50 znakova');
            return;
        }


        //odjel
        if(strlen(trim($bolnica->odjel))===0){
            $this->novoView($bolnica, 'Odjel obvezno');
        return;
        }

        if(strlen(trim($bolnica->odjel))>50){
            $this->novoView($bolnica, 'Odjel ne moze imati više od 50 znakova');
            return;
        }
        

        //doktor
        if(strlen(trim($bolnica->doktor))===0){
            $this->novoView($bolnica, 'Doktor obvezno');
        return;
        }

        if(strlen(trim($bolnica->doktor))>50){
            $this->novoView($bolnica, 'Doktor ne moze imati više od 50 znakova');
            return;
        }


        //ovdje sam siguran da je sve ok s kontrolama
       Bolnica::dodajNovu($bolnica);
       $this->index();
        
    }   
    
     private function novoView($bolnica, $poruka)
    {
        $this->view->render($this->viewDir . 'novo',[
            'bolnica'=>$bolnica,
            'poruka'=>'$poruka'
        ]);
    }


}    