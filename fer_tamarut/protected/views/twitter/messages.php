<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title></title>
</head>
<body>

<?php
/* @var $this TwitterController */

$this->breadcrumbs=array(
	'Twitter',
);
if ($status==0){
    $keys=array_keys($korisnik_primio);
    echo "<table border=1  cellspacing=0 cellpading=0>
             <strong>Komunikacija putem poruka</strong> </table>";
             echo "<table border=1 cellspacing=0 cellpading=0>
             <tr> <td><strong>Ime prijatelja</strong></td> <td><strong>Broj primljenih poruka od prijatelja</strong></td> <td><strong>Broj poslanih poruka prijatelju</strong></td> </tr>";
    foreach($keys as $value){
            // echo nl2br( "<strong>$imenaprijatelja[$value]</strong> broj lajkova: $lajkovi[$value]    broj komentara: $komentari[$value]    broj postova: $postovi[$value]\n");
            if(array_key_exists($value,$imena)){        
   		   echo "
             <tr> <td>$imena[$value]</td> <td>$korisnik_primio[$value]</td><td>$korisnik_poslao[$value]</td></tr>
             ";
			} 
			
    }
    echo "</table>";
} else if($status==2){
    echo nl2br("Upisali ste neispravan broj pokušajte ponovno!");
} 

?>