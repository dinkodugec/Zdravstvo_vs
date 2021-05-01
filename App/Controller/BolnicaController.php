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
        $this->view->render($this->viewDir . 'novo');
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $bolnica = new stdClass();
            $bolnica->naziv='';
            $bolnica->ravnatelj='';
            $bolnica->odjel='';
            $bolnica->doktor='';
            $this->view->render($this->viewDir . 'novo',[
                'bolnica'=>$bolnica,
                'poruka'=>'Popunite sve podatke'
            ]);
            return;
        }

    $bolnica = (object) $_POST;

    if(strlen(trim($bolnica->naziv))===0){
        $this->view->render($this->viewDir . 'novo',[
            'bolnica'=>$bolnica,
            'poruka'=>'Naziv obavezno'
        ]);
        return;
    }
      


    }
    
}