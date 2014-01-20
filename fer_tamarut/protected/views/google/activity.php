<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title></title>
</head>
<body>

<?php
/* @var $this GoogleController */

$this->breadcrumbs=array(
	'Google',
);
if ($status==0){
    $keys=array_keys($objave);
    echo "<table border=1  cellspacing=0 cellpading=0>
             <strong>Objave, plusevi i komentari prijatelja</strong> </table>";
             echo "<table border=1 cellspacing=0 cellpading=0>
             <tr> <td><strong>Ime prijatelja</strong></td> <td><strong>Broj objava</strong></td> <td><strong>Broj pluseva</strong></td> <td><strong>Broj komentara</strong></td> </tr>";
    foreach($keys as $value){
            // echo nl2br( "<strong>$imenaprijatelja[$value]</strong> broj lajkova: $lajkovi[$value]    broj komentara: $komentari[$value]    broj postova: $postovi[$value]\n");
            if(array_key_exists($value,$imena)){        
   		    echo "
             <tr> <td>$imena[$value]</td> <td>$objave[$value]</td> <td>$plusevi[$value]</td> <td>$komentari[$value]</td></tr>
             ";
			} 
			
    }
    echo "</table>";
} else if($status==2){
    echo nl2br("Upisali ste neispravan broj pokušajte ponovno!");
}
?>