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

    public static function dodajNovu($bolnica)        //kada odradim sve kontrole
    {                                                  // u bolnici
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            insert into bolnica (naziv,ravnatelj,odjel,doktor)
            values (:naziv,:ravnatelj,:odjel,:doktor)
        
        ');
        $izraz->execute((array)$bolnica);
    }

}
