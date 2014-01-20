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
    $keys=array_keys($retweet_friend);
    echo "<table border=1  cellspacing=0 cellpading=0>
             <strong>Retweetovi korisnika </strong> </table>";
             echo "<table border=1 cellspacing=0 cellpading=0>
             <tr> <td><strong>Ime prijatelja</strong></td> <td><strong>Broj puta koliko vas je prijatelj spomenuo</strong></td><td><strong>Broj puta koliko ste odgovorili korisniku</strong></td> </tr>";
    foreach($keys as $value){
            // echo nl2br( "<strong>$imenaprijatelja[$value]</strong> broj lajkova: $lajkovi[$value]    broj komentara: $komentari[$value]    broj postova: $postovi[$value]\n");
            if(array_key_exists($value,$imena)){        
   		    echo "
             <tr> <td>$imena[$value]</td> <td>$retweet_friend[$value]</td><td>$retweet_user[$value]</td></tr>
             ";
			} 
			
    }
    echo "</table>";
} else if($status==2){
    echo nl2br("Upisali ste neispravan broj pokušajte ponovno!");
} 

?>