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
?>
<form action="messages" method="post">
Upisite za koliko dana unazad želite pregledavati komunikaciju putem poruka(najviše 14): <input type="text" name="broj_inbox">
<input type="submit">
</form>
<form action="friend_re" method="post">
Upisite za koliko dana unazad želite pregledavati retweetove prijatelja na vaše objave (najviše 90): <input type="text" name="broj_friend">
<input type="submit">
</form>
<form action="home" method="post">
Upisite ključne riječi odvojene zarezom kako bi saznali što su ljudi koje pratite tweetali o toj temi: <input type="text" name="home_rijeci">
te za koliko dana unazad želite tražiti (najviše 7) <input type="text" name="home_broj">
<input type="submit">
</form>
<form action="interests" method="post">
Upisite ključne riječi odvojene zarezom kako bi saznali prijatelje sa sličnim interesima: <input type="text" name="interesi">
<input type="submit">
</form>
<form action="refresh" method="post">
Osvježite bazu podataka 
<input type="submit" name="Osvježi">
</form>
<form action="clearsessions" method="post">
Očistite sesiju<input type="submit">
</form>
<?php
if($status==1){
    echo nl2br("U bazi podataka nisu postojali podaci o vama! Baza podataka je osvježena!");
} else if($status==3){
    echo nl2br("Podaci o vama su postojali u bazi ali nisu bili ažurirani s današnjim datumom. Baza podataka je osvježena!");
}
?>