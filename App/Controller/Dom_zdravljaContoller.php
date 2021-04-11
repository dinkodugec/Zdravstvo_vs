<?php

class Dom_zdravlja extends AutorizacijaController
{
    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'smjer'
                        . DIRECTORY_SEPARATOR;

    public function index()
    {
        $this->view->render($this->viewDir . 'index',[
            'domovizdravlja'=>Dom_Zdravlja::ucitajSve()
        ]);
    }

    public function novo()
    {
        if($_SERVER['REQUEST_METHOD']==='GET'){
            $dom_zdravlja = new stdClass();
            $dom_zdravlja->doktor='';
            $dom_zdravlja->ordinacija='';
            this->view->render($this->viewDir . 'novo',[
                'dom_zdravlja'=>$dom_$zdravlja,
                'poruka'=>'Popunite podatke'
    
            ]);
            return;
        }

        $dom_zdravlja = (object) $_POST;

        if(strlen(trim($smjer->naziv))===0){
        this->view->render($this->viewDir . 'novo',[
            'dom_zdravlja'=>$dom_$zdravlja,
            'poruka'=>'Dom_Zdravlja obavezno'

        ]);
        return;
        }
    }

}