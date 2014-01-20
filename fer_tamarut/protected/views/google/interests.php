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
    $keys=array_keys($osobe);
	if(count($osobe)>0){
    echo "<table border=1  cellspacing=0 cellpading=0>
             <strong>Osobe u krugovima</strong> </table>";
             echo "<table border=1 cellspacing=0 cellpading=0>
             <tr> <td><strong>Ime osobe</strong></td> <td><strong>Opis</strong></td></tr>";
    foreach($keys as $value){
            // echo nl2br( "<strong>$imenaprijatelja[$value]</strong> broj lajkova: $lajkovi[$value]    broj komentara: $komentari[$value]    broj postova: $postovi[$value]\n");
            if(array_key_exists($value,$imena)){        
   		    echo "
             <tr> <td><strong>$imena[$value]</strong></td> <td>$osobe[$value]</td></tr>
             ";
			} 
			
    }
    echo "</table>";
	} else
	   echo nl2br("Nema nijedne osobe u krugovima sa sličnim interesima!\n");
	
	
	$keys=array_keys($stranice);
	if(count($stranice)>0){
    echo "<table border=1  cellspacing=0 cellpading=0>
             <strong>Stranice u krugovima</strong> </table>";
             echo "<table border=1 cellspacing=0 cellpading=0>
             <tr> <td><strong>Ime stranice</strong></td> <td><strong>Opis</strong></td></tr>";
    foreach($keys as $value){
            // echo nl2br( "<strong>$imenaprijatelja[$value]</strong> broj lajkova: $lajkovi[$value]    broj komentara: $komentari[$value]    broj postova: $postovi[$value]\n");
            if(array_key_exists($value,$imena)){        
   		    echo "
             <tr> <td><strong>$imena[$value]</strong></td> <td>$stranice[$value]</td></tr>
             ";
			} 
    }
    echo "</table>";
	} else
	   echo nl2br("Nema nijedne stranice u krugovima sa sličnim interesima!\n");
	   
	   
} else if($status==2){
    echo nl2br("Niste upisali ništa, pokušajte ponovno!");
}

?>