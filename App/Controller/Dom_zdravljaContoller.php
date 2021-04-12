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
            $this->novoView($dom_zdravlja,'popunite sve podatke');
            return;
        }

        $dom_zdravlja = (object) $_POST;

        if(strlen(trim($dom_zdravlja->naziv))===0){
        $this->novoView($dom_zdravlja,'Naziv obvezno');
        return;
        }

        if(strlen(trim($dom_zdravlja->naziv))>50){
            $this->novoView($dom_zdravlja,'Naziv ne moze imati vise od 50 znakova');
            return;
            }


        if(strlen(trim($dom_zdravlja->ordinacija))===0){
                $this->novoView($dom_zdravlja,'Naziv obvezno');
                return;
                }
        
        if(strlen(trim($dom_zdravlja->ordinacija))>50){
                    $this->novoView($dom_zdravlja,'Naziv ne moze imati vise od 50 znakova');
                    return;
                    }    
    }


    private function novoView($dom_zdravlja, $poruka)
    {
        this->view->render($this->viewDir . 'novo',[
            'dom_zdravlja'=>$dom_zdravlja,
            'poruka'=>$poruka

        ]);
    }
    

}