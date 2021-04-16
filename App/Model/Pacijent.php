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
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();
    
    }

}


}













