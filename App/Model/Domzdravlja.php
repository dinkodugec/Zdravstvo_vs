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
        
        select a.sifra, a.naziv, a.doktor, a.bolnica, a.ordinacija,
        b.ime, b.prezime,b.oib,b.domzdravlja,b.lijek, 
        count(c.sifra) as ukupnopacijenata
        from domzdravlja a
        inner join pacijent b on a.sifra =b.domzdravlja
        left join lijek c on a.sifra=c.bolest 
        group by a.sifra, b.ime, b.prezime, c.naziv;

        
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
            (ime, prezime, oib, domzdravlja, lijek) values
            (:ime, :prezime, :oib, :domzdravlja, :lijek)
            
        ');
        $izraz->execute([
            'ime'=>$entitet->ime,
            'prezime'=>$entitet->prezime,
            'domzdravlja'=>$entitet->domzdravlja,
            'lijek'=>$entitet->lijek,
            'oib'=>$entitet->oib
        ]);
        $zadnjaSifra=$veza->lastInsertId();
        $izraz=$veza->prepare('
        
            insert into domzdravlja 
            (naziv, doktor, bolnica,ordinacija) values
            (:naziv, :doktor, :bolnica, :ordinacija)
        
        ');
        $izraz->execute([
            'pacijent'=>$zadnjaSifra,
            'oib'=>$entitet->oib
        ]);

        $veza->commit();
    }


    public static function promjeniPostojeci($entitet)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $izraz=$veza->prepare('
        
            select bolnica from domzdravlja where sifra=:sifra
            
        ');
       
        $izraz->execute(['sifra'=>$entitet->sifra]);
        $sifraBolnica=$izraz->fetchColumn();

        $izraz=$veza->prepare('
        
            update bolnica
            set naziv=:naziv, ravnatelj=:ravnatelj, odjel=:odjel, doktor=:doktor
            where sifra=:sifra
            
        ');
        $izraz->execute([
            'naziv'=>$entitet->naziv,
            'ravnatelj'=>$entitet->ravnatelj,
            'odjel'=>$entitet->odjel,
            'doktor'=>$entitet->doktor,
            'sifra'=>$sifraBolnica
        ]);

        $izraz=$veza->prepare('
        
            update domzdravlja 
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
        
          select bolnica from domzdravlja where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        $sifraBolnica=$izraz->fetchColumn();

        $izraz=$veza->prepare('
        
            delete from domzdravlja where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifra]);


        $izraz=$veza->prepare('
        
            delete from bolnica where sifra=:sifra
        
        ');
        $izraz->execute(['sifra'=>$sifraBolnica]);

        $veza->commit();
    }
}