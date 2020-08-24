<?php 
require_once("config.php"); 
require_once("class-phpass.php"); 
global $db;

$db = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
if(!$db){
	die( "Sorry! There seems to be a problem connecting to our database.");
}

function login($email, $pass){
	global $db; 
	$email = mysqli_real_escape_string($db, $email);
	$password = mysqli_real_escape_string($db, $pass);

	$sql = "SELECT id, password FROM users WHERE email='".$email."' LIMIT 1 ";		
	$result = mysqli_query($db, $sql);
	$id = mysqli_fetch_row($result);
	if($id){
		$password_hashed = $id[1];
		$wp_hasher = new PasswordHash(16, true);
		if($wp_hasher->CheckPassword($password, $password_hashed)) {
			return $id[0];
		}
	} else {
		return false;
	}
}

function usernameExist($user){
	global $db; 
	$username = mysqli_real_escape_string($db, $user);
	$sql = "SELECT id FROM users WHERE username='".$username."' LIMIT 1 ";		
	$result = mysqli_query($db, $sql);
	$id = mysqli_fetch_row($result);
	return ($id[0] > 0);
}

function emailExist($email){
	global $db; 
	$Email = mysqli_real_escape_string($db, $email);
	$sql = "SELECT id FROM users WHERE email='".$Email."' LIMIT 1 ";		
	$result = mysqli_query($db, $sql);
	$id = mysqli_fetch_row($result);
	return ($id[0] > 0);
}

function register(/*$full_name,*/ $user, $pass, $emailId){
	global $db; 
	//$full_name = mysqli_real_escape_string($db, $full_name);
	$username = mysqli_real_escape_string($db, $user);
	$password = mysqli_real_escape_string($db, $pass);
	$email = mysqli_real_escape_string($db, $emailId);

	$wp_hasher = new PasswordHash(16, true);
			$pass = $wp_hasher->HashPassword( trim( $password ) );
			
	$sql = "INSERT INTO users ( email,password,username) VALUES ( '".$email."', '".$pass."', '".$username."') ";		
	$result = mysqli_query($db, $sql);
	return  mysqli_insert_id($db);
}

function logout(){
	unset($_SESSION['user_id']);
	session_destroy();	
	header('Location: index.php');
	exit();
}

function addLike($userid, $postid) {
	$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	$conn->query("UPDATE users SET upvotes = CONCAT(users.upvotes, ' ', $postid, ' ') WHERE id = $userid");
	$sql = "SELECT views, entries, ups, downs FROM posty WHERE id = $postid";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	$upd_ups = $conn->query("UPDATE posty SET ups = ups+1 WHERE id = $postid");
	$conn->close();
}

function addDislike($userid, $postid) {
	$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	
	$conn->query("UPDATE users SET downvotes = CONCAT(users.downvotes, ' ', $postid, ' ') WHERE id = $userid");
	$result = $conn->query("SELECT views, entries, ups, downs FROM posty WHERE id = $postid");
	$row = $result->fetch_assoc();
	$upd_downs = $conn->query("UPDATE posty SET downs = downs+1 WHERE id = $postid");
	$conn->close();
}

function displayVotes($postid, $rating) {
	if(isset($_SESSION['user_id'])) {
		$userid = $_SESSION['user_id'];
		$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "SELECT upvotes, downvotes FROM users WHERE id = $userid";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$upvotes = $row["upvotes"];
		$downvotes = $row["downvotes"];
		$search = ' '.$postid.' ';
		$conn->close();
		
		
		if (strpos($upvotes, $search) !== false) {
			echo "<form action='index.php' method='post'>
			<button class='nPressed' name='unUpvote' value=$postid>+</button>
			<div style='float:left;'> $rating </div>
			<button class ='nButton' name='counterDown' type=submit value=$postid>-</button></form>";
		} else if(strpos($downvotes, $search) !== false) {
			echo "<form action='index.php' method='post'>
			<button class='nButton' name='counterUp' value=$postid>+</button>
			<div style='float:left;'> $rating </div>
			<button class ='nPressed' name='unDownvote' type=submit value=$postid>-</button></form>";
		} else {
			echo "<form action='index.php' method='post'>
			<button class='nButton' name='up' value=$postid>+</button>
			<div style='float:left;'> $rating </div>
			<button class ='nButton' name='down' type=submit value=$postid>-</button></form>";
		} 
	} else {
		echo "<button class='nButton' onclick='logAlert()'>+</button>
			<div style='float:left;'> $rating </div>
			<button class='nButton' onclick='logAlert()'>-</button>";
	}
}

function unUpvote($postid) {
	$userid = $_SESSION['user_id'];
	$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "UPDATE users SET upvotes = REPLACE(upvotes, $postid, '') WHERE id = $userid";
		$conn->query("UPDATE posty SET ups = ups-1 WHERE id = $postid");
		$conn->query($sql);
		$conn->close();
		
}

function unDownvote($postid) {
	$userid = $_SESSION['user_id'];
	$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "UPDATE users SET downvotes = REPLACE(downvotes, $postid, '') WHERE id = $userid";
		$conn->query("UPDATE posty SET downs = downs-1 WHERE id = $postid");
		$conn->query($sql);
		$conn->close();
		
}

function updateRanking($postid) {
	$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$result = $conn->query("SELECT entries, views, ups, downs FROM posty WHERE id = $postid");
		$row = $result->fetch_assoc();
		$rating = (($row["entries"]+$row["ups"]+$row["downs"]-5)*(($row["ups"]+1)/($row["downs"]+1)))/($row["views"]+1);
		$upd_rating = $conn->query("UPDATE posty SET rating = $rating WHERE id = $postid");
}

function addPost($title, $desc, $img, $cont) {
	if(isset($_SESSION['user_id'])) {
		$userid = $_SESSION['user_id'];
		$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "INSERT INTO posty (title, img, description, content, authorid) VALUES ('$title', '$img', '$desc', '$cont', '$userid')";
		$conn->query($sql);
		$sql = "SELECT id FROM posty ORDER BY id DESC LIMIT 1";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		return $row["id"];
		$conn->close();
	}
}

function isseen($postid) {
	if(isset($_SESSION['user_id'])) {
		$userid = $_SESSION['user_id'];
		$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "SELECT seen FROM users WHERE id = $userid";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$seen = $row["seen"];
		$conn->close();
		$search = ' '.$postid.' ';
		if (strpos($seen, $search) !== false) {
			return 1;
		} else {
			return 0;
		}
	} else {
		return 0;
	}
}

function displayComments($postid) {
	if (isset($_SESSION['user_id'])) $userid = $_SESSION['user_id'];
	$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
	$sql = "SELECT id, authorid FROM comments WHERE postid = $postid";
	$result = $conn->query($sql);
	$added = 0;
	$counter = 0;
	if ($result->num_rows > 0) {
		while ($row = $result->fetch_assoc()) {
			if (isset($_SESSION['user_id'])) { if($row['authorid']==$userid) $added = 1; }
			$counter++;
		}
	} else  echo "Komentarze: 0";
	if($counter!=0&&$added==1) echo "<span style='color: green;'> Komentarze: " .$counter ."</span>";
	else if($counter!=0) echo "Komentarze: " .$counter;
	$conn->close();
}
//mysqli_close($db);