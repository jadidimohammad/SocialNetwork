<?php 
session_start();
include("includes/connection.php");
include("functions/functions.php");

if(!isset($_SESSION['user_email'])){
	
	header("location: index.php"); 
}
else {
?>
<!DOCTYPE html> 
<html>
	<head>
		<title>Welcome User!</title> 
	<link rel="stylesheet" href="styles/home_style.css" media="all"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	</head> 
	
<body>
	
	<div class="container">
		
		
				<ul class="menu">
					<li><a href="home.php">Home</a></li>
					<li><a href="members.php">Members</a></li>
					
					<?php 
					
					$get_topics = "select * from topics"; 
					$run_topics = mysqli_query($con,$get_topics);
					
					while($row=mysqli_fetch_array($run_topics)){
						
						$topic_id = $row['topic_id'];
						$topic_title = $row['topic_title'];
					
					echo "<li><a href='topic.php?topic=$topic_id'>$topic_title</a></li>";
					}
					
					?>
					<form method="get" action="results.php" id="form1">
					<input class="search" name="user_query" type="text" placeholder="Search">
					</form>
				</ul>

			

			<div class="profile">
			
				<div id="user_timeline">
					<div id="user_mention">
					<?php 
					$user = $_SESSION['user_email'];
					$get_user = "select * from users where user_email='$user'"; 
					$run_user = mysqli_query($con,$get_user);
					$row=mysqli_fetch_array($run_user);
					
					$user_id = $row['user_id']; 
					$user_name = $row['user_name'];
					$user_country = $row['user_country'];
					$user_image = $row['user_image'];
					$register_date = $row['register_date'];
					$last_login = $row['last_login'];
					
					$user_posts = "select * from posts where user_id='$user_id'"; 
					$run_posts = mysqli_query($con,$user_posts); 
					$posts = mysqli_num_rows($run_posts);
					
					//getting the number of unread messages 
					$sel_msg = "select * from messages where receiver='$user_id' AND status='unread' ORDER by 1 DESC"; 
					$run_msg = mysqli_query($con,$sel_msg);		
		
					$count_msg = mysqli_num_rows($run_msg);
					
					
					echo "
						<center>
						<img src='user/user_images/$user_image' width='200' height='200'/>
						</center><hr>
						<div >
						<p><strong>Name:</strong> $user_name</p>
						<p><strong>Country:</strong> $user_country</p>
						<p><strong>Last Login:</strong> $last_login</p>
						<p><strong>Member Since:</strong> $register_date</p>
						
						<button><a href='my_messages.php?inbox&u_id=$user_id'>Messages ($count_msg)</a></button>
						<button><a href='my_posts.php?u_id=$user_id'>My Posts ($posts)</a></button>
						<button><a href='edit_profile.php?u_id=$user_id'>Edit My Account</a></button>
						<button><a href='logout.php'>Logout</a></button>
						</div>
					";
					?>
					</div>
				</div>
				</div>
	
				<div class="content_timeline">
				<div class="spost">
					<form action="home.php?id=<?php echo $user_id;?>" method="post" id="f">
					<h2>What's your question today? let's discuss!</h2>
					<input class="a2"type="text" name="title" placeholder="Write a Title..." size="50" required="required"/><br/> 
					<textarea class="a2"cols="50" rows="4" name="content" placeholder="Write description..."></textarea><br/>
					<select name="topic">
						<option>Select Topic</option>
						<?php getTopics();?>
					</select>
					<input type="submit" name="sub" value="Post to Timeline"/>
					</form>
					<?php insertPost();?>
					</div>
						<h3 id="a1">Recent Discussions!</h3> 
						<?php get_posts();?>
				</div>
	
		
	</div>


</body>
</html>
<?php } ?>