<?php

class Domzdravlja 
{

    public static function ucitaj($sifra)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            select * from domzdravlja where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();
    
    }


    public static function ucitajSve()
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
           select * from domzdravlja

        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    public static function dodajNovi($domzdravlja)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            insert into domzdravlja (naziv,doktor,bolnica,ordinacija)
            values (:naziv,:doktor,:bolnica,:ordinacija)
        ');
        $izraz->execute((array)$domzdravlja);
    }

    public static function promjeniPostojeci($domzdravlja)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            update domzdravlja set
            naziv=:naziv,doktor=:doktor,
            bolnica=:bolnica,ordinacija=:ordinacija
            where sifra=:sifra
            
        ');
       
        $izraz->execute((array)$domzdravlja);

    }


    public static function obrisiPostojeci($sifra)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            delete * from domzdravlja where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        
    
    }
}