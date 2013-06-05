<?php

require_once('../classes/config.class.php');
require_once('../classes/core.class.php');
require_once('../classes/core.layout.class.php');
$core = Core::getInstance();
$eStr = ''; //Error string
$adminUser = '';
session_start();
if(isset($_GET['logout'])) {
	Core::logout();
}
if($_POST) {
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

<html>
<head>
	<title>Create Page - CMS</title>
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<script src="../resources/scripts/jquery-1.10.1.min.js"></script>
	<script src="../resources/scripts/tinymce/tinymce.min.js"></script>
	<script>
		tinymce.init({
		    selector: "textarea"
		 });
	</script>
	<link rel="stylesheet" type="text/css" href='../resources/styles/style.css' />
</head>
<body id="admin">
	<did id="wrapper">
		<div id="header">
			<div id="nav">
				<?php CoreLayout::getNav() ?>
			</div>
		</div>
		<div id="main">
			<div class="clear"></div>
			<?php if($eStr): ?><div id="error"><?php echo $eStr; ?></div><?php endif; ?>
			<h2 class="title">Create a new page</h2>
			<form action="" method="post" id="newPage">
				<label for="title">Page Title</label>
				<div class="clear"></div>
				<input type="text" value="<?php echo isset($_POST['title']) ? $_POST['title'] : '';?>" name="title" />
				<div class="clear"></div>
				<label for="title">Page Content</label>
				<div class="clear"></div>
				<textarea name="content"><?php echo isset($_POST['title']) ? $_POST['title'] : '';?></textarea>
				<div class="clear"></div>
			</form>
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
			</script>
		</div>
	</div>
</body>
</html>
<?php else : CoreLayout::loginPage($eStr,$adminUser); endif; ?>
