<?php
define("MAX_POST_AMOUNT",     "10");
define("MAX_NEWS_AMOUNT",     "3");
require_once 'config.php';

$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>

<div class="left_col">
	<div class="left_col_top">
	<?php
	if(isset($_SESSION['user_id'])) {
		$id=$_SESSION['user_id'];
		$sql = "SELECT username, img FROM users WHERE id = $id";
		$result = $conn->query($sql);
		
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$img = $row["img"];
			echo "<img class='imgUser' src=$img>";
			echo " ". $row["username"];
			echo "<form action='index.php' method='get'>
				 <button class='nButton' name='dodajPost' type=submit value='nowy'>
					Dodaj nowy  post
				 </button>
				 </form>";
		}
	} else {
		echo "Niezalogowany użytkownik</br>
			  Zaloguj się, by móc dodawać nowe posty";
	}
	include 'newest.php';
	?>
	</div>
</div>

<div class="right_col">
<?php
$sql = "SELECT id, authorid, title, content, entries, img, description, views, ups, downs FROM posty ORDER BY rating DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	$break = 0;
    while(($row = $result->fetch_assoc())&&$break<MAX_POST_AMOUNT) {
		$title = $row["title"];
		$desc = $row["description"];
		$id = $row["id"];
		$img = $row["img"];
		$likeValue = $row["ups"] - $row["downs"];
		$authorid = $row["authorid"];
		$result2 = $conn->query("SELECT img, username FROM users WHERE id = $authorid");
		$row2 = $result2->fetch_assoc();
		$authorimg = $row2["img"];
		$authorname = $row2["username"];
		if(isseen($id)) $ifseen = "postSeen"; else $ifseen = "post";
			echo 
			"<a href='?article=$id'><div class=$ifseen>
			<img class='imgUser' src=$authorimg> <div class='authorName'>$authorname</div> <div style='clear: both;'></div>
			<div class='title'>$title</div>
			<img class='imgBig' src='$img'/> 
			<div class='description'>$desc</div></a>";
			displayVotes($id, $likeValue);
			displayComments($id);
			if($row["content"]==""){echo "<div style='clear: both;'></div></div>";}  
			else { echo "<a href='?article=$id'><span style='color: blue; float: right;'>
			Zobacz całość</span></a><div style='clear: both;'></div></div>";}
		
		$upd_views = $conn->query("UPDATE posty SET views = $row[views]+1  WHERE id = $row[id]");
		updateRanking($row["id"]);
		$break++;
    }
} else echo "0 results";
$conn->close();
?>
</div>