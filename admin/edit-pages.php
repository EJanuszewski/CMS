<?php
$start = microtime();
require_once('../classes/config.class.php');
require_once('../classes/core.class.php');
require_once('../classes/core.layout.class.php');
require_once('../classes/page.class.php');
$core = Core::getInstance();
$eStr = ''; //Error string
$adminUser = '';
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
if(isset($_POST['title'])) {
	//If they post a title
	Page::newPage($_POST['title'],$_POST['content']);
}

?>
<?php if(isset($_SESSION['session']['admin']) && $_SESSION['session']['admin'] == 1) : ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Edit Pages - CMS</title>
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<script src="../resources/scripts/jquery-1.10.1.min.js"></script>
	<script src="../resources/scripts/tinymce/tinymce.min.js"></script>
	<link rel="stylesheet" type="text/css" href='../resources/styles/style.css' />
</head>
<body id="admin">
	<div id="wrapper">
		<div id="header">
			<div id="nav">
				<?php CoreLayout::getNav() ?>
			</div>
		</div>
		<div id="main">
			<div class="clear"></div>
			<?php if($eStr): ?><div id="error"><?php echo $eStr; ?></div><?php endif; ?>
			<h2 class="title">Edit Pages</h2>
			<?php Page::GetPageList() ?>
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
			</script>
		</div>
	</div>
</body>
</html>
<?php else : header('Location:'.Config::read('baseUrl').'/admin'); endif;
$loadTime = microtime()-$start;
echo 'Page generated in: '.$loadTime.'s';?>