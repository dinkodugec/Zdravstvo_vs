<?php

class Bolnica
{

    public static function ucitajSve()
    {

        $veza = DB::getInstanca();    //spajanje na bazu
        $izraz = $veza->prepare('          
        
              select * from bolnica

        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

}
