<?php

class Pacijent 
{

    public static function ucitaj($sifra)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
       
        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();


    }


    public static function ucitajSve()
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();


    }


    public static function dodajNovi($entitet)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz=$veza->prepare('
        
             insert into lijek 
             (naziv, bolest, proizvodac,cijena) values
             (:naziv, :bolest, :proizvodac, :cijena)

           
        ');
        $izraz->execute([
            'naziv'=>$entitet->naziv,
            'bolest'=>$entitet->bolest,
            'proizvodac'=>$entitet->proizvodac,
            'cijena'=>$entitet->cijena
        ]);

        $zadnjaSifrA=$veza->lastInsertId();
        $izraz=$veza->prepare('
        
             insert into pacijent 
             (ime, prezime, oib, dom_zdravlja, lijek, bolestan) values
             (:ime, :prezime, :oib, :dom_zdravlja, :lijek, :bolestan)

           
        ');
        $izraz->execute([
            'ime'=>$entitet->ime,
            'prezime'=>$entitet->prezime,
            'oib'=>$entitet->oib,
            'dom_zdravlja'=>$entitet->dom_zdravlja,
            'lijek'=>$entitet->lijek,
            'bolestan'=>$entitet->bolestan
        ]);

        $veza->commit();
    }


    public static function promjeniPostojeci($entitet)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz=$veza->prepare('
        
            select lijek from pacijent where sifra=:sifra
            
        ');
       
        $izraz->execute(['sifra'=>$entitet->sifra]);
        $sifraOsoba=$izraz->fetchColumn();

        $izraz=$veza->prepare('

        update lijek
        set naziv=:naziv, bolest=:bolest, proizvodac=:proizvodac
        where sifra=:sifra

        ');
        $izraz->execute([
            'naziv'=>$entitet->naziv,
            'bolest'=>$entitet->bolest,
            'proizvodac'=>$entitet->oib,
            'sifraLijek'=>$entitet->sifra
            
        ]);

        $izraz=$veza->prepare('
        
            update pacijent
            set oib=:oib
            where sifra=:sifra
    
        ');
        $izraz->execute([
            'sifra'=>$entitet->sifra,
            'oib'=>$entitet->oib
        ]);



        $veza->commit();

    }
}


}













