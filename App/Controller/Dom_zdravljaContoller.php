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
        $this->view->render($this->viewDir . 'novo',[
            'domovizdravlja'=>Dom_Zdravlja::ucitajSve()
        ]);
    }

}