<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>title</title>
	<meta name="description" content="description">
	<meta name="keywords" content="key words">
	<meta http-equiv="X-UA_Compatible" content="IF-EDGE, chrome=1">
	<link rel="stylesheet" type="text/css" href="style3.css">
	<link rel="stylesheet" type="text/css" href="styleButtons.css">
	<link rel="stylesheet" type="text/css" href="stylePosts2.css">
	<script src="functions.js"></script>
	<script type='text/javascript' src='recoverscroll.js'></script>
	<script type='text/javascript'>
	RecoverScroll.start( "homePage" );
	</script>
</head>
<body>
	<div class="bar">
		<div class="menu"></div> 
		<div class="login">
		<?php
		require_once("functions.php");
		
		if(isset($_SESSION['user_id'])) {
		  echo "<a class='login' href='logout.php?action=logout'> Wyloguj </a>";
		} else
		{ echo "<a class='login' href='login.php'> Zaloguj </a>"; }
		?>
		</div>
		<div class="logo">negatyw.pl</div>
	</div>
	
	
	<div class="container">
		
		<?php 
			if(isset($_POST["up"])) {
				addLike($_SESSION['user_id'], $_POST["up"]);
				updateRanking($_POST["up"]);
				include 'cont.php'; }
			else if(isset($_POST["down"])) {
				addDislike($_SESSION['user_id'], $_POST["down"]);
				updateRanking($_POST["down"]);
				include 'cont.php'; }
			else if(isset($_POST["unUpvote"])) {
				unUpvote($_POST["unUpvote"]);
				updateRanking($_POST["unUpvote"]);
				include 'cont.php'; }
			else if(isset($_POST["unDownvote"])) {
				unDownvote($_POST["unDownvote"]);
				updateRanking($_POST["unDownvote"]);
				include 'cont.php'; }
			else if(isset($_POST["counterUp"])) {
				unDownvote($_POST["counterUp"]);
				addLike($_SESSION['user_id'], $_POST["counterUp"]);
				updateRanking($_POST["counterUp"]);
				include 'cont.php'; }
			else if(isset($_POST["counterDown"])) {
				unUpvote($_POST["counterDown"]);
				addDislike($_SESSION['user_id'], $_POST["counterDown"]);
				updateRanking($_POST["counterDown"]);
				include 'cont.php'; }
			else if(isset($_GET["article"]))
				include 'article.php';
			else if((isset($_GET["dodajPost"]))||(isset($_POST["submitPost"])))
				include 'addpost.php';
			else
				include 'cont.php'; 
		?>
		
	</div>
	
</body>