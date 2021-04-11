<?php

class Dom_Zdravlja
{

    public static function ucitajSve()
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
            select * from dom_zdravlja
        
        ');
        $izraz->execute()
        return $izraz->fetchAll();
    
    }

}