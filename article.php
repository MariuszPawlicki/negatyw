<div class="article">
<?php
require_once 'config.php';
require_once 'functions.php';

$postid = $_GET["article"];
$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
$conn2 = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT content, authorid, title, img, description, views, entries, ups, downs FROM posty WHERE id=$postid";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
	$title = $row['title'];
	$authorid = $row['authorid'];
	$cont = $row['content'];
	$img = $row['img'];
	$desc = $row['description'];
	$likeValue = $row["ups"]-$row["downs"];
	$result2 = $conn2->query("SELECT img, username FROM users WHERE id = $authorid");
	$row2 = $result2->fetch_assoc();
	$authorimg = $row2["img"];
	$authorname = $row2["username"];
    echo "
	<img class='imgUser' src='$authorimg'>
	<div class='authorName'>$authorname</div><div style='clear: both;'></div>
	<h1> $title </h1> <img src='$img'/> </br> $desc </br> $cont";
	displayVotes($postid, $likeValue);
	echo "<div style='clear: both;'></div>";
		
	$upd_entries = $conn->query("UPDATE posty SET entries = entries+1  WHERE id = $postid");
	if(isset($_SESSION['user_id'])) {
		$userid = $_SESSION['user_id'];
		$upd_seen = $conn->query("UPDATE users SET seen = CONCAT(users.seen, ' ', $postid, ' ')  WHERE id = $userid");
	}
	updateRanking($postid);
} else {
    echo "0 results";
}
$conn2->close();
$conn->close();
include 'commentSection.php';
?>
</div>