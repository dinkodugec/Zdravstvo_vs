<?php

class Dom_Zdravlja
{

    public static function ucitajSve()
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            select * from dom_zdravlja
        
        ');
        $izraz->execute()
        return $izraz->fetchAll();
    
    }

    public static function dodajNovi($dom_zdravlja)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            insert into dom_zdravlja (doktor,bolnica,ordinacija)
            values (:doktor,:bolnica,:ordinacija)
        ');
        $izraz->execute((array)$dom_zdravlja);
    }

}