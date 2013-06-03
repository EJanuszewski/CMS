<?php

require_once('../classes/config.class.php');
require_once('../classes/core.class.php');
$core = Core::getInstance();
$eStr = '';
$baseUrl;
session_start();
$_SESSION['admin'] = $_SESSION['admin'];
if(isset($_GET['logout'])) {
	$_SESSION['admin'] = 0;
	session_destroy();
    if (strpos($_SERVER['HTTP_HOST'],'http://') === false){
        $baseUrl = 'http://'.$_SERVER['HTTP_HOST'];
    } else {
        $baseUrl = $_SERVER['HTTP_HOST'];
    }
	header("Location:".$baseUrl);
}
if($_POST) {
	$adminUser = $_POST['username'];
	$adminPassword = $_POST['password'];
	$s = false; //Success

	//Check they all have a value
	if($adminUser != '' && $adminPassword != '') {
		//Hash
		$dynamics = md5(substr($adminUser, 0, strlen($adminUser)/2) . $adminPassword);
		$salt = substr($dynamics, 0, 16);
		$pepper = substr($dynamics, 16);
		$hashedPass = hash('sha512', $salt.$adminPassword.$pepper);
		//Try login
		//Perform the SQL
		$q = $core->dbh->query('SELECT * FROM `users` WHERE `username` = "'.$adminUser.'" AND `password` = "'.$hashedPass.'"');
		$r = $q->rowCount();
		if($r == 1) {
			//Login successful
			//Set the cookie so they see the admin panel
			$_SESSION['admin'] = 1;
		} else {
			$eStr = 'Username or password wrong, please try again.';
			$_SESSION['admin'] = 0;
		}
	}

}

?>
<?php if($_SESSION['admin'] == 1) { ?>

<html>
<head>
	<title>Admin Login - CMS</title>
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<script src="../resources/scripts/jquery-1.10.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href='../resources/styles/style.css' />
</head>
<body id="admin">
	<did id="wrapper">
		<div id="header">
			<div id="nav">
				<ul>
					<li><a href="#">Home</a></li>
					<li><a href="#">Pages</a>
						<ul>
							<li><a href="#">Create New Page</a></li>
							<li><a href="#">Edit Pages</a></li>
						</ul>
					</li>
					<li class="logout"><a href="?logout">Logout</a></li>
				</ul>
			</div>
		</div>
		<div id="main">
			<?php if($eStr) { ?><div id="error"><?php echo $eStr; ?></div><?php } ?>
			<script>
				$(document).ready(function() {
					$("#error").fadeIn("2000");
				});
				$("ul li").hover(function() {
					$(this).children("ul").clearQueue();
					$(this).children("ul").slideDown("slow");
				}, function() {
					$(this).children("ul").delay(1000).slideUp("fast");
				});
				$("form").submit(function(e) {
					c = 0;
					$("form .item.input input").each(function() {
						if($(this).val() == '') {
							c+=1;
							$(this).parent().children(".err").fadeIn("slow");
						} else {
							$(this).parent().children(".err").fadeOut("slow");
						}
					});
					if(c == 0) {
						return true;
					} else {
						return false;
					}
				});
			</script>
		</div>
	</div>
</body>
</html>

<?php } else { ?>
<html>
<head>
	<title>Admin Login - CMS</title>
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<script src="../resources/scripts/jquery-1.10.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href='../resources/styles/style.css' />
</head>
<body id="admin">
	<did id="wrapper">
		<div id="header">
			<h1>Admin Login</h1>
		</div>
		<div id="main">
			<?php if($eStr) { ?><div id="error"><?php echo $eStr; ?></div><?php } ?>
			<h2>Please login below</h2>
			<form action="" method="POST">
				<div class="item input">
					<label>Username</label>
					<input type="text" value="<?php echo isset($username) ? $username : '';?>" name="username" />
					<div class="err">Please enter your username</div>
				</div>
				<div class="item input">
					<label>Password</label>
					<input type="password" value="" name="password" />
					<div class="err">Please enter your password</div>
				</div>
				<div class="item">
					<input type="submit" value="Login" name="submit" />
				</div>
			</form>
			<script>
				$(document).ready(function() {
					$("#error").fadeIn("2000");
				});
				$("form").submit(function(e) {
					c = 0;
					$("form .item.input input").each(function() {
						if($(this).val() == '') {
							c+=1;
							$(this).parent().children(".err").fadeIn("slow");
						} else {
							$(this).parent().children(".err").fadeOut("slow");
						}
					});
					if(c == 0) {
						return true;
					} else {
						return false;
					}
				});
			</script>
		</div>
	</div>
</body>
</html>
<?php } ?>