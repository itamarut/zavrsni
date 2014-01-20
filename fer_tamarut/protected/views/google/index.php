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
?>

<form action="activity" method="post">
Upisite za koliko dana unazad želite promatrati svoje aktivnosti(najviše 90): <input type="text" name="broj_activity">
<input type="submit">
</form>
<form action="interests" method="post">
Upisite ključne riječi odvojene zarezom kako bi saznali prijatelje sa sličnim interesima: <input type="text" name="rijeci">
<input type="submit">
</form>
<form action="refresh" method="post">
Osvježite bazu podataka 
<input type="submit" name="Osvježi">
</form>

<form action="clearsessions" method="post">
Očistite sesiju
<input type="submit" name="Osvježi">
</form>
<?php
if($status==1){
    echo nl2br("U bazi podataka nisu postojali podaci o vama! Baza podataka je osvježena!");
} else if($status==3){
    echo nl2br("Podaci o vama su postojali u bazi ali nisu bili ažurirani s današnjim datumom. Baza podataka je osvježena!");
}
?>