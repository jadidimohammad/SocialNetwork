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
	</head> 
	
<body>
	<!--Container starts--> 
	<div class="container">
		<!--Header Wrapper Starts-->
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
				</ul>

			
			<!--Header ends-->

		<!--Header Wrapper ends-->
			<!--Content area starts-->
			<div class="profile">
				<!--user timeline starts-->
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
				<!--user timeline ends-->
				<!--Content timeline starts-->
				<div id="content_timeline">
					
					<h2>All Registered Users on this Site:</h2><br/>
					
					<?php 
					$get_members = "select * from users"; 
					$run_members = mysqli_query($con,$get_members);
					
					while($row=mysqli_fetch_array($run_members)){
					
					$user_id = $row['user_id']; 
					$user_name = $row['user_name'];
					$user_image = $row['user_image'];
					
					echo "
					<a href='user_profile.php?u_id=$user_id'>
					<img src='user/user_images/$user_image' width='50' height='50' title='$user_name'/>
					</a>
						
					";
					}
		
					?>
				</div>
				<!--Content timeline ends-->
			</div>
			<!--Content area ends-->
		
	</div>
	<!--Container ends-->

</body>
</html>
<?php } ?>