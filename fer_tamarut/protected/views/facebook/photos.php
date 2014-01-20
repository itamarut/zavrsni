<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title></title>
</head>
<body>

<?php
/* @var $this FacebookController */

$this->breadcrumbs=array(
	'Facebook',
);
if ($status==0){
    $keys=array_keys($objave);
   echo "<table border=1  cellspacing=0 cellpading=0>
             Broj objavljenih fotografija na kojima je korisnik označen,lajkova i komentara na tim fotografijama! </table>";
             echo "<table border=1 cellspacing=0 cellpading=0>
             <tr> <td><font >Prijatelj</td> <td>Broj objavljenih fotografija</td> <td>Broj komentara</td> <td>Broj lajkova</font></td></tr>";
    foreach($keys as $value){
            // echo nl2br( "<strong>$imena[$value]</strong> broj objava: $lajkovi[$value]    broj komentara: $komentari[$value]    broj postova: $postovi[$value]\n");
             echo "
             <tr> <td><font ><strong>$imena[$value]</strong></td> <td>$objave[$value]</td> <td>$komentari[$value]</td> <td>$lajkovi[$value]</font></td></tr>
             ";     
    }
    echo "</table>";
} else if($status==2){
    echo nl2br("Upisali ste neispravan broj pokušajte ponovno!");
} 
?>