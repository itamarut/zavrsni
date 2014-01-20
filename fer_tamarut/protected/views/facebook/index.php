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
?>

<form action="messages" method="post">
Upisite za koliko dana unazad želite pregledavati komunikaciju putem poruka(najviše 14): <input type="text" name="broj_inbox">
<input type="submit">
</form>
<form action="wall" method="post">
Upisite za koliko dana unazad želite pregledavati interakciju s prijateljima na vašim objavama na zidu (najviše 90): <input type="text" name="broj_wall">
<input type="submit">
</form>
<form action="photos" method="post">
Upisite za koliko dana unazad želite pregledavati interakciju na temelju slika na kojima ste označeni (najviše 90): <input type="text" name="broj_photos">
<input type="submit">
</form>
<form action="friendinterests" method="post">
Upisite ključne riječi odvojene zarezom kako bi saznali prijatelje sa sličnim interesima: <input type="text" name="friend_interests">
<input type="submit">
</form>
<form action="refresh" method="post">
Osvježite bazu podataka 
<input type="submit" name="Osvježi">
</form>
<?php
if($status==1){
    echo nl2br("U bazi podataka nisu postojali podaci o vama! Baza podataka je osvježena!");
} else if($status==3){
    echo nl2br("Podaci o vama su postojali u bazi ali nisu bili ažurirani s današnjim datumom. Baza podataka je osvježena!");
}
?>
