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
										<form method="get" action="results.php" id="form1">
					<input class="search" name="user_query" type="text" placeholder="Search">
					</form>
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
				<?php 
					if(isset($_GET['post_id'])){
						
						$get_id = $_GET['post_id']; 
						
						$get_post = "select * from posts where post_id='$get_id'"; 
						$run_post = mysqli_query($con,$get_post); 
						$row=mysqli_fetch_array($run_post); 
						
						$post_title = $row['post_title']; 
						$post_con = $row['post_content']; 
					
					}
				?>
					<form action="" method="post" id="f">
					<h2>Edit Your Post:</h2>
					<input type="text" name="title" value="<?php echo $post_title;?>" size="82" required="required"/><br/> 
					<textarea cols="83" rows="4" name="content"><?php echo $post_con;?></textarea><br/>
					<select name="topic">
						<option>Select Topic</option>
						<?php getTopics();?>
					</select>
					<input type="submit" name="update" value="Update Post"/>
					</form>
					
					<?php 
					if(isset($_POST['update'])){
					
					$title = $_POST['title']; 
					$content = $_POST['content'];
					$topic = $_POST['topic'];
					
					$update_post = "update posts set post_title='$title', post_content='$content', topic_id='$topic' where post_id='$get_id'";
					$run_update = mysqli_query($con,$update_post); 
					
					if($run_update){
					
					echo "<script>alert('Post has been updated!')</script>";
					echo "<script>window.open('home.php','_self')</script>";
					
					}
					
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