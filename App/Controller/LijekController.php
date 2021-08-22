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

        if(isset($_GET['uvjet'])){
            $uvjet='%' . $_GET['uvjet'] . '%';
        }else{
            $uvjet='%';
            $_GET['uvjet']='';
        }
        /* $lijekovi = Lijek::ucitajSve();
        
        foreach($lijekovi as $red){
            if(file_exists(BP . 'public' . DIRECTORY_SEPARATOR .
            'img' . DIRECTORY_SEPARATOR . 'lijek' . 
            DIRECTORY_SEPARATOR . $red->sifra . '.png')){
                $red->slika = App::config('url') . 
                'public/img/lijek/' . $red->sifra . '.png';  //ako postoji datoteka na disku tada je $red slika
            }else{
                $red->slika = App::config('url') . 
                'public/img/lijek/nepoznato.png';
            }
        }*/

       $limit=5;
        $this->view->render($this->viewDir . 'index',[
            'entiteti'=>Lijek::ucitajPaginacija($limit),
            'paginacija'=>$this->paginacija(Lijek::pobroji(), $limit),
            'uvjet'=>$_GET['uvjet']
        ]); 
      
      
        /* $this->paginacija(); */
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
       /*  $this->entitet->bolest=''; */
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
       /*  $this->kontrolaBolest(); */
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

    /* private function kontrolaBolest()
    {
        if(strlen(trim($this->entitet->bolest))==0){
            throw new Exception('Bolest obavezno');
        }

        if(strlen(trim($this->entitet->bolest))>50){
            throw new Exception('Bolest predugačko');
        }
    } */

    /* public function paginacija($link, $broj_stranica, $aktivna = 1)  */
    public function paginacija($broj_komada, $stavki_po_stranici= 10)
   {
      /* echo "<pre>"; print_r($_SERVER);
       die(); */

       $link = $_SERVER["REDIRECT_URL"];
       $aktivna= isset($_GET["stranica"]) ? $_GET["stranica"] : 1;
       
       $broj_stranica=ceil($broj_komada/$stavki_po_stranici);
       $html =  '<nav aria-label="Pagination">
       <ul class="pagination text-center">';
   
     
   
       if ($aktivna != 1) {
           $href = $link . '?stranica=1';
           $html .= '<li><a aria-label="Page '.$aktivna.'"  href="' . $href . '">&lt;&lt;</a></li>';
           $prethodna = $aktivna - 1;
           $href = $link . '?stranica='.$prethodna;
           $html .= '<li><a aria-label="Page '.$prethodna.'" href="' . $href . '">&lt;</a></li>';
       }
       for ($i = 1; $i <= $broj_stranica; $i++) {
           $href = $link . '?stranica='.$i;
         /*   $class_aktivna = ''; */
           if ($aktivna == $i) {
            $html .= '<li><strong>'.$i.'</strong></li>';
           } else{

          $html .= '<li><a aria-label="Page '.$i.'"  href="' . $href . '">'.$i.'</a></li>';
        }
       }
   
       if ($aktivna < $broj_stranica) {
           $sljedeca = $aktivna + 1;
           $href = $link . '?stranica='.$sljedeca;
           $html .= '<li><a aria-label="Page '.$sljedeca.'" href="' . $href . '">&gt;</a></li>';
           $href = $link . '?stranica='.$broj_stranica;
           $html .= '<li><a aria-label="Page '.$broj_stranica.'" href="' . $href . '">&gt;&gt;</a></li>';
       }
       $html .= '</ul>
       </nav>';

       return $html;
   }


  /*  public function paginacija($link = "test-url", $broj_stranica = 10, $aktivna = 2)
   {
       $html =  '<nav aria-label="Pagination">
       <ul class="pagination text-center">';
   
     
   
       if ($aktivna != 1) {
           $href = $link . '?stranica=1';
           $html .= '<li><a class="link" href="' . $href . '">&lt;&lt;</a></li>';
           $prethodna = $aktivna - 1;
           $href = $link . '?stranica='.$prethodna;
           $html .= '<li><a class="link" href="' . $href . '">&lt;</a></li>';
       }
       for ($i = 1; $i <= $broj_stranica; $i++) {
           $href = $link . '?stranica='.$i;
           $class_aktivna = '';
           if ($aktivna == $i) {
               $class_aktivna = " aktivna";
           }
           $html .= '<li><a class="link'.$class_aktivna.'" href="' . $href . '">'.$i.'</a></li>';
       }
   
       if ($aktivna < $broj_stranica) {
           $sljedeca = $aktivna + 1;
           $href = $link . '?stranica='.$sljedeca;
           $html .= '<li><a class="link" href="' . $href . '">&gt;</a></li>';
           $href = $link . '?stranica='.$broj_stranica;
           $html .= '<li><a class="link" href="' . $href . '">&gt;&gt;</a></li>';
       }
       $html .= '</ul>
       </nav>';

       return $html;
   } */



}

