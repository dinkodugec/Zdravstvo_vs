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
        
        select  a.*, c.naziv as bolnica, count(b.sifra) as ukupnopacijenata
        from domzdravlja a
        left join pacijent b on a.sifra =b.domzdravlja
        inner join bolnica c on a.bolnica=c.sifra
        group by a.naziv ,b.domzdravlja;
       
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();
    }

    


    public static function dodajNovi($entitet)
    {
        try {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        /*$izraz=$veza->prepare('
        
            insert into pacijent 
            (ime, prezime, oib, domzdravlja, lijek) values
            (:ime, :prezime, :oib, :domzdravlja, :lijek)
            
        '); 

        $izraz->execute([
            'ime'=>$entitet->ime,
            'prezime'=>$entitet->prezime,
            'oib'=>$entitet->oib,
            'domzdravlja'=>$entitet->domzdravlja,
            'lijek'=>$entitet->lijek
         ]);
        */
          //$zadnjaSifra=$veza->lastInsertId(); 
          $izraz=$veza->prepare('
        
            insert into domzdravlja 
            (naziv, mjesto, bolnica) values
            (:naziv, :mjesto, :bolnica)
        
        ');

        $izraz->execute([
            'naziv'=>$entitet->naziv,
            'mjesto'=>$entitet->mjesto,
            'bolnica'=>$entitet->bolnica
    
        ]);

        
       /* $izraz->execute([
            'pacijent'=>$zadnjaSifra,
            
        ]); */

        $veza->commit();
        
    } catch(PDOException $e) {
         echo "<br>" . $e->getMessage();
      }

    }


    public static function promjeniPostojeci($entitet)
    {
        try {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        /* $izraz=$veza->prepare('
        
            select bolnica from domzdravlja where sifra=:sifra
            
        ');
       
        $izraz->execute(['sifra'=>$entitet->sifra]);
        $sifraBolnica=$izraz->fetchColumn(); */

       /*  $izraz=$veza->prepare('
        
            update bolnica
            set naziv=:naziv, ravnatelj=:ravnatelj, odjel=:odjel, doktor=:doktor
            where sifra=:sifra
            
        '); */
        /* $izraz->execute([
            'naziv'=>$entitet->naziv,
            'ravnatelj'=>$entitet->ravnatelj,
            'odjel'=>$entitet->odjel,
            'doktor'=>$entitet->doktor,
            'sifra'=>$sifraBolnica
        ]); */

        $izraz=$veza->prepare('
        
            update domzdravlja 
            set naziv=:naziv, bolnica=:bolnica
            where sifra=:sifra
    
        ');
        $izraz->execute([
            'sifra'=>$entitet->sifra,
            'naziv'=>$entitet->naziv,
            'bolnica'=>$entitet->bolnica
        ]);

        
        /* $izraz->debugDumpParams(); */
       /*  die(); */



        $veza->commit();
    } catch(PDOException $e) {
        echo "<br>" . $e->getMessage();
     }

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

    public static function dodajPacijenta()
    {

        $veza = DB::getInstanca(); 
        $izraz=$veza->prepare('
        
            insert into pacijent where sifra=:sifra 
        
        ');
        $izraz->execute($_POST);
        return $izraz->fetch();
    
    }

    public static function obrisiPacijenta()
    {

        $veza = DB::getInstanca(); 
        $izraz=$veza->prepare('
        
            delete pacijent where sifra=:sifra 
        
        ');
        $izraz->execute($_POST);
        return $izraz->fetch();
    
    }
}