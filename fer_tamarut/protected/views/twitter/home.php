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
    $keys=array_keys($tweet_sadrzaj);
    echo "<table border=1  cellspacing=0 cellpading=0>
             <strong>Tweetovi putem ključnih riječi</strong> </table>";
             echo "<table border=1 cellspacing=0 cellpading=0>
             <tr> <td><strong>Vlasnik tweeta</strong></td> <td><strong>Tweet</strong></td></tr>";
    foreach($keys as $value){
            // echo nl2br( "<strong>$imenaprijatelja[$value]</strong> broj lajkova: $lajkovi[$value]    broj komentara: $komentari[$value]    broj postova: $postovi[$value]\n");
            if(array_key_exists($tweet_vlasnik[$value],$imena)){  
                $c=$tweet_vlasnik[$value];			
   		   echo "
             <tr> <td>$imena[$c]</td> <td>$tweet_sadrzaj[$value]</td></tr>
             ";
			} 
			
    }
    echo "</table>";
} else if($status==2){
    echo nl2br("Niste zadali ispravan upit! Pokušajte ponovno!");
} 

?>