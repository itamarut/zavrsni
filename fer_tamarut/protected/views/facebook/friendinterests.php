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
    $keys=array_keys($moje_stranice);
    echo "<table border=1  cellspacing=0 cellpading=0>
             <strong>Zajednički interesi korisnika</strong> </table>";
             echo "<table border=1 cellspacing=0 cellpading=0>
             <tr> <td><strong>Ime lajkane stranice</strong></td> <td><strong>Prijatelji koji su također lajkali</strong></td> </tr>";
    foreach($keys as $value){
            
			echo "
            <tr> <td>$moje_stranice[$value]</td><td>
            ";
			if (array_key_exists($value,$lajkovi_prijatelja)){
			    $rijeci=explode(",", $lajkovi_prijatelja[$value]);
                foreach($rijeci as $id){
                    if(array_key_exists($id,$imena)){
                       	echo "$imena[$id],   ";
                    }
                }
            }
            echo "</td></tr>";			
    }
    echo "</table>";
} else if($status==2){
    echo nl2br("Niste upisali ništa, pokušajte ponovno!");
}
?>