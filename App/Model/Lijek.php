<?php

class Lijek
{

    public static function ucitaj($sifra)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
           select a.sifra, a.naziv, a.proizvodac,  
           b.ime, b.prezime
           from lijek a inner join pacijent b
           on a.sifra=b.lijek;

        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();


    }



    public static function ucitajSve()
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.sifra, a.naziv, a.proizvodac,  
        b.ime, b.prezime AS ukupnopacijenata
        from lijek a inner join pacijent b  
        on a.sifra=b.lijek;
     
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();


    }


    public static function dodajNovi($entitet)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz=$veza->prepare('
        
            insert into pacijent
            (ime, prezime, oib, domzdravlja) values
            (:ime, :prezime, :oib, :domzdravlja)
            
        ');
        $izraz->execute([
            'ime'=>$entitet->ime,
            'prezime'=>$entitet->prezime,
            'oib'=>$entitet->oib,
            'domzdravlja'=>$entitet->domzdravlja

        ]);
        $zadnjaSifra=$veza->lastInsertId();
        $izraz=$veza->prepare('
        
            insert into lijek 
            (naziv, bolest,) values
            (:naziv, :bolest)
        
        ');
        $izraz->execute([
          'naziv'=>$zadnjaSifra,
          'bolest'=>$entitet->bolest
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
        $sifraLijek=$izraz->fetchColumn();


        $izraz=$veza->prepare('
        
            update pacijent
             set ime=:ime, prezime=:prezime, oib=:oib, domzdravlja=:domzdravlja, lijek=:lijek
             where sifra=:sifra
            
            
        ');
        $izraz->execute([
            'ime'=>$entitet->ime,
            'prezime'=>$entitet->prezime,
            'oib'=>$entitet->oib,
            'domzdravlja'=>$entitet->domzdravlja,
            'sifra'=>$sifraLijek
            
        ]);


        $izraz=$veza->prepare('
        
            update lijek
            set naziv=:naziv
            where sifra=:sifra
    
        ');
        $izraz->execute([
            'sifra'=>$entitet->sifra,
            'naziv'=>$entitet->naziv
        ]);



        $veza->commit();

    }


    public static function obrisiPostojeci($sifra)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz=$veza->prepare('
        
          select pacijent from lijek where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        $sifraLijek=$izraz->fetchColumn();

        $izraz=$veza->prepare('
        
            delete from lijek where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);


        $izraz=$veza->prepare('
        
            delete from pacijent where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifraLijek]);

        $veza->commit();
    }
}