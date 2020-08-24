<?php
if(isset($_POST['commentSubmit'])) {
	if($_POST['commentContent']!='') {
		echo "<script> alert('Dodano komentarz'); </script>";
		$userid = $_SESSION['user_id'];
		$content = $_POST['commentContent'];
		$conn2 = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
		if ($conn2->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
		$sql = "INSERT INTO comments (authorid, postid, content) VALUES ('$userid', '$postid', '$content')";
		$conn2->query($sql);
		$conn2->close();
	} else  echo "<script> alert('Wpisz treść komentarza'); </script>";
}

if(isset($_SESSION['user_id'])) {
		echo 
	    "<form action='?article=$postid' method='post'>
	    <input type='text' name='commentContent'>
	    <button type='submit' name='commentSubmit' value='$postid'>Dodaj komentarz</button>
	    </form>";
	} else
	{ echo "Zaloguj się, by dodać komentarz"; }

$conn = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
$conn2 = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "SELECT id, authorid, content, ups, downs, postid, commentid FROM comments WHERE postid=$postid ORDER BY id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	$break = 0;
    while($row = $result->fetch_assoc()) {
		$id = $row["id"];
		$content = $row["content"];
		$likeValue = $row["ups"] - $row["downs"];
		$authorid = $row["authorid"];
		$result2 = $conn2->query("SELECT img, username FROM users WHERE id = $authorid");
		$row2 = $result2->fetch_assoc();
		$authorimg = $row2["img"];
		$authorname = $row2["username"];
		echo 
		"<div class='comment'>
		<img class='imgUser' src=$authorimg>
		<div class='authorName'>$authorname</div><div style='clear: both;'></div>
		<div class='content'>$content</div>
		<button onclick='showCommentForm()'>Odpowiedz</buton>
		<<form action='?article=$postid' method='post'>
		</form>
		</div>";
    }
} else echo "Brak komentarzy";
$conn2->close();
$conn->close();
?>