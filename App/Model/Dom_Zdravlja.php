<?php

class Dom_Zdravlja
{

    public static function ucitaj($sifra)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            select * from dom_zdravlja where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();
    
    }


    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
           select * from dom_zdravlja

        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function dodajNovi($dom_zdravlja)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            insert into dom_zdravlja (naziv,doktor,bolnica,ordinacija)
            values (:naziv,:doktor,:bolnica,:ordinacija)
        ');
        $izraz->execute((array)$dom_zdravlja);
    }

    public static function promjeniPostojeci($dom_zdravlja)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            update dom_zdravlja set
            naziv=:naziv,doktor=:doktor,
            bolnica=:bolnica,ordinacija=:ordinacija
            where sifra=:sifra
            
        ');
       
        $izraz->execute((array)$dom_zdravlja);

    }


    public static function obrisiPostojeci($sifra)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            delete * from dom_zdravlja where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        
    
    }
}