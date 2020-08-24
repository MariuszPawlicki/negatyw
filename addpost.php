<?php
require_once 'functions.php';

if(isset($_POST["submitPost"])) {
	$case = 0;
	if($_POST["title"]=="") 
		echo "Pole tytuł nie może być puste</br>"; 
	else
		$case=1;
	
	if($case) { 
		echo "Dodano nowy post</br>";
		$result = addPost($_POST["title"], $_POST["desc"], $_POST["img"], $_POST["content"]);
		echo "ID twojeo posta to ".$result."</br>Moższ go śledzić <a href='?article=$result'><span style='color: blue;'>tutaj</span></a>";
	}
}
echo
"<h2> Nowy post: </h2>
<form action='index.php' method='post'>
	<h3> Tytuł </h>
	<input type='text' name='title'>
	<h3> Opis </h>
	<input type='text' name='desc'>
	<h3> Dodaj url zdjęcia </h>
	<input type='text' name='img'></br></br>
	<h> Dodaj więcej treści </h>
	<input type='text' name='content'>
	<button type='submit' name='submitPost'>Dodaj</button>
</form>";
?>