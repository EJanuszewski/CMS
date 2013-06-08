<?php
if($_POST) {
	$host = $_POST['host'];
	$dbName = $_POST['db_name'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	$n = 0;
	$s = false;
	//Check they all have a value
	foreach ($_POST as $key => $value) {
		if($value != '') {
			$n++;
		}
	}
	if($n >= 4) {
		$err = 0;
		$eStr = '';
       try {
		//Try connect to the database
			$dsn = 'mysql:host='.$host.';dbname='.$dbName.';port=3306;connect_timeout=15';
	       	$testDb = new PDO($dsn, $username, $password);               	
       } catch(PDOException $e) {
			$eStr =  $e->getMessage();
			$err = 1;
       }
       	if($err == 0) {
			//Write the config
			$file = file_get_contents('classes/config.class.sample.php');
			$file = str_replace("{DBHOST}", $host, $file);
			$file = str_replace("{DBNAME}", $dbName, $file);
			$file = str_replace("{DBUSER}", $username, $file);
			$file = str_replace("{DBPASSWORD}", $password, $file);
			//Check BaseUrl has http
			$baseUrl = $_POST['baseUrl'];
			require_once('classes/config.class.php');
			if(isset(Config::$confArray['baseUrl']) && Config::$confArray['baseUrl'] != '{BASEURL}') $baseUrl = Config::$confArray['baseUrl'];
			if (strpos($baseUrl,'http://') === false){
	            $baseUrl = 'http://'.$baseUrl;
	        }
			$file = str_replace("{BASEURL}", $baseUrl, $file);
			if(file_put_contents('classes/config.class.php', $file)) {
				require_once('classes/core.class.php');
				$core = Core::getInstance();
				$s = true; //Set success to true
			} else {
				echo 'An Error occured, please ensure config.class.php is writeable';
			}

			//Perform the SQL
			$q = $core->dbh->prepare('CREATE TABLE IF NOT EXISTS `pages` (`id` int(11) NOT NULL AUTO_INCREMENT,`content` text NOT NULL,`title` varchar(255) NOT NULL,`template` int(11) NOT NULL,PRIMARY KEY (`id`)) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;CREATE TABLE IF NOT EXISTS `templates` (`id` int(11) NOT NULL AUTO_INCREMENT,`content` text NOT NULL,`title` varchar(255) NOT NULL,PRIMARY KEY (`id`)) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;CREATE TABLE IF NOT EXISTS `users` (`id` int(11) NOT NULL AUTO_INCREMENT,`username` varchar(255) NOT NULL,`password` varchar(255) NOT NULL,PRIMARY KEY (`id`)) DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ');
			$q->execute();

			//Add the admin user
			$adminUser = $_POST['adminUser'];
			$adminPassword = $_POST['adminPassword'];

			$hashedPass = Core::getHash($adminUser,$adminPassword);
			$q = $core->dbh->prepare('INSERT INTO `users` (`username`,`password`) VALUES(?,?)');
			$q->execute(array($adminUser,$hashedPass));

		}
	}

}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Install - CMS</title>
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<script src="resources/scripts/jquery-1.10.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href='resources/styles/style.css' />
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<h1>CMS Installation</h1>
			<p>This will create your config and install the database</p>
		</div>
		<div id="main">
			<?php if($s == true) { ?><div id="success">Successfully created config file and setup database, you may view your admin at <a href="<?php echo $baseUrl.'/admin'; ?>"><?php echo $baseUrl.'/admin'; ?></a></div><?php } ?>
			<?php if($eStr) { ?><div id="error"><?php echo $eStr; ?></div><?php } ?>
			<h2>Please fill in your database details below</h2>
			<form action="" method="POST">
				<div class="item">
					<label>Base URL</label>
					<input type="text" value="<?php echo isset($baseUrl) ? $baseUrl : $_SERVER['HTTP_HOST']?>" name="baseUrl" />
					<div class="err">Please fill in a base URL value</div>
				</div>
				<div class="item">
					<label>Host</label>
					<input type="text" value="<?php echo isset($host) ? $host : '';?>" name="host" />
					<div class="err">Please fill in a host value</div>
				</div>
				<div class="item">
					<label>Database name</label>
					<input type="text" value="<?php echo isset($dbName) ? $dbName : '';?>" name="db_name" />
					<div class="err">Please fill in your database name</div>
				</div>
				<div class="item">
					<label>Username</label>
					<input type="text" value="<?php echo isset($username) ? $username : '';?>" name="username" />
					<div class="err">Please fill in your database username</div>
				</div>
				<div class="item">
					<label>Password</label>
					<input type="text" value="<?php echo isset($password) ? $password : '';?>" name="password" />
					<div class="err">Please fill in your password</div>
				</div>
				<div class="item">
					<label>Admin Username</label>
					<input type="text" value="<?php echo isset($adminUser) ? $adminUser : '';?>" name="adminUser" />
					<div class="err">Please fill in your password</div>
				</div>
				<div class="item">
					<label>Admin Password</label>
					<input type="text" value="<?php echo isset($adminPassword) ? $adminPassword : '';?>" name="adminPassword" />
					<div class="err">Please fill in your password</div>
				</div>
				<div class="item">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</form>
			<script>
				$(document).ready(function() {
					$("#success").fadeIn(2000);
					$("#error").fadeIn(2000);
				});
				$("form").submit(function(e) {
					c = 0;
					$("form input[type=text]").each(function() {
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