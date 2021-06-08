<?php

//https://stackify.com/display-php-errors/
// odkomentirati kada želimo vidjeti sva upozorenja i greške

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



session_start();

//echo __DIR__;
define('BP',__DIR__ . DIRECTORY_SEPARATOR);
define('BP_APP',__DIR__ . DIRECTORY_SEPARATOR 
            . 'App' . DIRECTORY_SEPARATOR);

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

spl_autoload_register(function($klasa){
    $putanje = explode(PATH_SEPARATOR,get_include_path());
    foreach($putanje as $p){ 
        //echo $p . DIRECTORY_SEPARATOR . $klasa . '.php' . '<br>'; 
        if (file_exists($p . DIRECTORY_SEPARATOR . $klasa . '.php')){
            include $p . DIRECTORY_SEPARATOR . $klasa . '.php';
        }
    }
});

App::start();