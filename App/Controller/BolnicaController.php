<?php

class BolnicaController extends AutorizacijaController
{

    private $viewDir = 'privatno'
                        . DIRECTORY_SEPARATOR
                        . 'bolnica'
                        . DIRECTORY_SEPARATOR;
    


    public function index()
    {
        $this->view->render($this->viewDir . 'index');
    }

}