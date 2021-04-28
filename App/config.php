<?php
$dev=$_SERVER['REMOTE_ADDR']==='127.0.0.1' ? true : false;
if($dev){
    $baza=[
        'server'=>'localhost',
        'baza'=>'zdravstvo_vukovarsko_srijemska_z',
        'korisnik'=>'edunova',
        'lozinka'=>'edunova'
    ];

    $url='http://zdravstvo.vs.hr:8080/';
}else{
    $baza=[
        'server'=>'localhost',
        'baza'=>'uran_pp22',
        'korisnik'=>'uran_edunova',
        'lozinka'=>'ronbetelges'
    ];
    $url='http://polaznik29.edunova.hr/';
}
return [
    'url'=>$url,
    'nazivApp'=>'zdravstvo vukovarsko srijemske županije',
    'baza'=>$baza
];











