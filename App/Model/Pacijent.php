<?php

class Pacijent 
{

    public static function ucitaj($sifra)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.sifra, a.ime, a.prezime, a.oib, b.naziv as lijek 
        from pacijent a 
        inner join lijek b
        on a.lijek = b.sifra
        where a.sifra=:sifra;
               
        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();


    }


    public static function ucitajSve()
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.sifra, a.ime, a.prezime, b.naziv  AS ukupnodomovazdravlja
        from pacijent a 
        inner join domzdravlja b
        on a.domzdravlja = b.sifra
        left join lijek c on a.lijek=c.sifra
         group by ime, prezime;
       
        
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
             (ime, prezime, oib, domzdravlja, lijek, bolestan) values
             (:ime, :prezime, :oib, :domzdravlja, :lijek, :bolestan)

           
        ');
        $izraz->execute([
            'ime'=>$entitet->ime,
            'prezime'=>$entitet->prezime,
            'oib'=>$entitet->oib,
            'domzdravlja'=>$entitet->domzdravlja,
            'lijek'=>$zadnjaSifrA

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


    public static function obrisiPostojeci($sifra)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz=$veza->prepare('
        
          select lijek from pacijent where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        $sifraLijek=$izraz->fetchColumn();

        $izraz=$veza->prepare('
        
            delete from pacijent where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);


        $izraz=$veza->prepare('
        
            delete from lijek where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifraLijek]);

        $veza->commit();
    }

}













