<?php

class Lijek
{

    public static function ucitaj($sifra)
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select * from lijek where sifra=:sifra 

        
        ');
        $izraz->execute(['sifra'=>$sifra]);
        return $izraz->fetch();


    }



    public static function ucitajSve()
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select *
        from lijek 
        group by naziv, proizvodac;
     
        
        ');
        $izraz->execute();
        return $izraz->fetchAll();


    }

    public static function pobroji()
    {

        $veza = DB::getInstanca();
        $izraz=$veza->prepare('
        
        select sifra
        from lijek;
     
        
        ');
        $izraz->execute();
        return count($izraz->fetchAll());


    }

    public static function ucitajPaginacija($limit=10)
    {

        $stranica=$_GET["stranica"] ?? 1;
        $offset=$stranica*$limit-$limit;
        $veza = DB::getInstanca();
        $izraz=$veza->prepare("
        
        select *
        from lijek 
        group by naziv, proizvodac
        limit $limit offset $offset
     
        
        ");

        $izraz->execute();
        return $izraz->fetchAll();
 

    }


    public static function dodajNovi($entitet,$file)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        $slikaNaziv = self::uploadPicture($file);
       

        $izraz=$veza->prepare('
        
            insert into lijek 
            (naziv, proizvodac, slika) values
            (:naziv, :proizvodac, :slika)
        
        ');
        $izraz->execute([
          'naziv'=>$entitet->naziv,
          'proizvodac'=>$entitet->proizvodac,
          'slika'=>$slikaNaziv


        ]);

        $veza->commit();
    }


    public static function promjeniPostojeci($entitet)
    {
        $veza = DB::getInstanca();
        $veza->beginTransaction();
        /* $izraz=$veza->prepare('
        
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
            
        ]); */


        $izraz=$veza->prepare('
        
            update lijek
            set naziv=:naziv, proizvodac=:proizvodac
            where sifra=:sifra
    
        ');
        $izraz->execute([
            'sifra'=>$entitet->sifra,
            'naziv'=>$entitet->naziv,
            /* 'bolest'=>$entitet->bolest, */
            'proizvodac'=>$entitet->proizvodac,

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

    public static function uploadPicture($file)
    {

        $target_dir = "public/img/lijek/";
       
       
        
        $target_file = $target_dir . basename($file['slika']["name"]);
         $uploadOk = 1;
         
        

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
          // if everything is ok, try to upload file
          } else {
            if (move_uploaded_file($file['slika']["tmp_name"], $target_file)) {
              return $target_file;
            } else {
              echo "Sorry, there was an error uploading your file.";
            }
          }

    }
}