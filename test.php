<?php
$suma=120000;
for($i=0; $i<25; $i++) {
	$suma=$suma+120000+(($suma+120000)*0.05);
	$wyswietl=$suma;
	echo "$wyswietl</br>";
}
?>