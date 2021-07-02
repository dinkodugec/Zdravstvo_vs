<?php

//https://stackify.com/display-php-errors/
// odkomentirati kada želimo vidjeti sva upozorenja i greške

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



session_start(); // globalna varijabla na serveru i podaci se pohranjuju dok je session aktivan i sacuvani su na serveru

//echo __DIR__;
define('BP',__DIR__ . DIRECTORY_SEPARATOR);    // funkcija define, ne mozemo joj mijenjati zadane vrijednosti
define('BP_APP',__DIR__ . DIRECTORY_SEPARATOR   // ugrađena konstanta koja vraca putanju gdje se file nalazi
            . 'App' . DIRECTORY_SEPARATOR);   // DIRECTORY_SEPARATOR  oznaka između direktorija (onaj blackslash)

//echo BP;

$putanje=implode(
    PATH_SEPARATOR,    
    [
        BP_APP . 'Model',
        BP_APP . 'Controller'
    ]
);

//echo $putanje;
set_include_path($putanje);

spl_autoload_register(function($klasa){                         //danas se koristi name space umjesto autoloading i ključna riječ use
    $putanje = explode(PATH_SEPARATOR,get_include_path());
    foreach($putanje as $p){ 
        //echo $p . DIRECTORY_SEPARATOR . $klasa . '.php' . '<br>'; 
        if (file_exists($p . DIRECTORY_SEPARATOR . $klasa . '.php')){
            include $p . DIRECTORY_SEPARATOR . $klasa . '.php';
        }
    }
});

App::start();