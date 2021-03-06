<?php

class Pacijent 
{


    public static function traziPacijenta()
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.*, b.naziv  
        from pacijent a 
        join domzdravlja b
        on a.domzdravlja = b.sifra
        left join lijek c on a.lijek=c.sifra
        where concat(a.ime, \'\', a.prezime,\'\',
        ifnull(b.naziv) like :uvjet and a.sifra not in
        (select sifra from pacijent where domzdravlja=:domzdravlja)       
        
        ');
        $izraz->execute([
            'uvjet'=>'%' . $_GET['uvjet'] . '%',
            'domzdravlja'=>$_GET['domzdravlja']
        ]);
        return $izraz->fetchAll();


    }

    public static function ucitaj($sifra)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.* 
        from pacijent as a
        where a.sifra=:sifra;
               
        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();


    }


    public static function ucitajSve()
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select a.*, b.naziv  
        from pacijent a 
        join domzdravlja b
        on a.domzdravlja = b.sifra
        left join lijek c on a.lijek=c.sifra
         group by a.ime, a.prezime;
       
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();


    }


    public static function dodajNovi($entitet)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction(); 
       /*  $izraz=$veza->prepare('
        
             insert into lijek 
             (naziv, bolest, proizvodac,cijena) values
             (:naziv, :bolest, :proizvodac, :cijena)

           
        '); */
       /*  $izraz->execute([
            'naziv'=>$entitet->naziv,
            'bolest'=>$entitet->bolest,
            'proizvodac'=>$entitet->proizvodac,
            'cijena'=>$entitet->cijena
        ]);
 */ 
       /*  $zadnjaSifrA=$veza->lastInsertId(); */
        $izraz=$veza->prepare('
        
             insert into pacijent 
             (ime, prezime, oib, domzdravlja, lijek) values
             (:ime, :prezime, :oib, :domzdravlja, :lijek)

           
        ');
        $izraz->execute([
            'ime'=>$entitet->ime,
            'prezime'=>$entitet->prezime,
            'oib'=>$entitet->oib,
            'domzdravlja'=>$entitet->domzdravlja,
            'lijek'=>$entitet->lijek,

        ]);

        $veza->commit();
    }


    public static function promjeniPostojeci($entitet)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        /* $izraz=$veza->prepare('
        
            select lijek from pacijent where sifra=:sifra
            
        '); */
       
        /* $izraz->execute(['sifra'=>$entitet->sifra]);
        $sifraLijek=$izraz->fetchColumn(); */

        /* $izraz=$veza->prepare('

        update lijek
        set naziv=:naziv, bolest=:bolest, proizvodac=:proizvodac
        where sifra=:sifra

        ');
        $izraz->execute([
            'naziv'=>$entitet->naziv,
            'bolest'=>$entitet->bolest,
            'proizvodac'=>$entitet->proizvodac,
            'sifra'=>$sifraLijek
            
        ]);
 */
        $izraz=$veza->prepare('
        
            update pacijent
            set oib=:oib, ime=:ime, prezime=:prezime, domzdravlja=:domzdravlja, lijek=:lijek
            where sifra=:sifra
    
        ');
        $izraz->execute([
            'sifra'=>$entitet->sifra,
            'oib'=>$entitet->oib,
            'ime'=>$entitet->ime,
            'prezime'=>$entitet->prezime,
            'domzdravlja'=>$entitet->domzdravlja,
            'lijek'=>$entitet->lijek
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













