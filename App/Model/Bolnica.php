<?php

class Bolnica
{


    public static function ucitaj($sifra)
    {

        $veza = DB::getInstanca();    //spajanje na bazu
        $izraz = $veza->prepare('          
        
              select * from bolnica where sifra = :sifra

        ');
        $izraz->execute();
        return $izraz->fetch();
    }

    public static function ucitajSve()
    {

        $veza = DB::getInstanca();    //spajanje na bazu
        $izraz = $veza->prepare('          
        
        select a.*, count(b.sifra) as ukupnodomovazdravlja from bolnica a
        left join domzdravlja b on a.sifra=b.bolnica 
        group by a.sifra, a.naziv, a.doktor, a.odjel,a.ravnatelj,a.doktor;
        

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

    public static function promjeniPostojecu($bolnica)
    {
        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
           update bolnica set 
           naziv=:naziv,ravnatelj=:ravnatelj,
           odjel=:odjel,doktor=:doktor 
           where sifra=:sifra
        
        ');
        $izraz->execute((array)$bolnica);
    }

    public static function obrisiPostojecu($sifra)
    {

        $veza = DB::getInstanca();   
        $izraz = $veza->prepare('          
        
              delete from bolnica where sifra = :sifra

        ');
        $izraz->execute(['sifra'=>$sifra]);
    
    }

}
