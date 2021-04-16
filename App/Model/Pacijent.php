<?php

class Pacijent 
{

public static function ucitajSve()
{

    public static function ucitaj($sifra)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            
        
        ');
        $izraz->execute();
        return $izraz->fetch();
    
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

}


}













