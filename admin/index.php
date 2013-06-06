<?php
$start = microtime();
require_once('../classes/config.class.php');
require_once('../classes/core.class.php');
require_once('../classes/core.layout.class.php');
$core = Core::getInstance();
$eStr = ''; //Error string
$adminUser = '';	
if(isset(Config::$confArray['baseUrl']) && Config::$confArray['baseUrl'] != '{BASEURL}') $baseUrl = Config::$confArray['baseUrl'];
session_start();
if(isset($_GET['logout'])) {
	Core::logout();
}
if(isset($_POST['login'])) {
	$adminUser = $_POST['username'];
	$adminPassword = $_POST['password'];
	$s = false; //Success bool

	//Check they all have a value
	if($adminUser != '' && $adminPassword != '') {
		Core::login($adminUser,$adminPassword);
	}

}

?>
<?php if(isset($_SESSION['session']['admin']) && $_SESSION['session']['admin'] == 1) : ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Home - CMS</title>
	<base href="http://www.ejanuszewski.com/Projects/Ninetwozero/CMS/" />
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<script src="resources/scripts/jquery-1.10.1.min.js"></script>
	<link rel="stylesheet" type="text/css" href='resources/styles/style.css' />
</head>
<body id="admin">
	<div id="wrapper">
		<div id="header">
			<div id="nav">
				<?php CoreLayout::getNav() ?>
			</div>
		</div>
		<div id="main">
			<?php if($eStr): ?><div id="error"><?php echo $eStr; ?></div><?php endif; ?>
			<script>
				$(document).ready(function() {
					$("#error").fadeIn(2000);
				});
				$("ul li").hover(function() {
					$(this).children("ul").clearQueue();
					$(this).children("ul").slideDown("slow");
				}, function() {
					$(this).children("ul").delay(1000).slideUp("fast");
				});
				$("form").submit(function(e) {
					c = 0; //Error count for validation
					//Loop through input's to check they aren't empty, if so fade in the error message
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
<?php else: CoreLayout::loginPage($eStr,$adminUser); endif; 
$loadTime = microtime()-$start;
echo 'Page generated in: '.$loadTime.'s';?>