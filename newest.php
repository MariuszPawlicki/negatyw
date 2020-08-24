<div class="newestPosts">
	<h3>Ostatnio dodane:</h3>
	<?php
		$sql = "SELECT id, authorid, title, content, entries, img, description, views, ups, downs FROM posty ORDER BY id DESC";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
		$break = 0;
			while(($row = $result->fetch_assoc())&&$break<MAX_NEWS_AMOUNT) {
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
				if(isseen($id)) $ifseen = "newPostSeen"; else $ifseen = "newPost";
				echo 
				"<a href='?article=$id'><div class=$ifseen>
				<img class='imgUser' src=$authorimg> <div class='authorName'>$authorname</div> <div style='clear: both;'></div>
				<div class='title'>$title</div>
				<img class='imgSmall' src='$img'/> 
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
		}
	?>
</div>