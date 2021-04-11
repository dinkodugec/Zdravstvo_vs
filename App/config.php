<?php
$dev=$_SERVER['REMOTE_ADDR']==='127.0.0.1' ? true : false;
if($dev){
    $baza=[
        'server'=>'localhost',
        'baza'=>'zdravstvo_vukovarsko_srijemska_z',
        'korisnik'=>'edunova',
        'lozinka'=>'edunova'
    ];
}else{
    $baza=[
        'server'=>'localhost',
        'baza'=>'uran_pp22',
        'korisnik'=>'uran_edunova',
        'lozinka'=>'ronbetelges'
    ];
}
return [
    'url'=>'http://zdravstvo.vs.hr/',
    'nazivApp'=>'zdravstvo vukovarsko srijem županije',
    'baza'=>$baza
];











