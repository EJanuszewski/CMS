<?php
$start = microtime();
require_once('../classes/config.class.php');
require_once('../classes/core.class.php');
require_once('../classes/core.layout.class.php');
require_once('../classes/page.class.php');
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
if(isset($_POST['title']) && isset($_GET['id']) == false && isset($_POST['id']) == false) {
	//If they post a title then add a new page
	$returnId = Page::newPage($_POST['title'],$_POST['content']);
	header("Location:".Config::read('baseUrl').'/admin/page/'.$returnId);
} elseif(isset($_POST['title']) && isset($_POST['content']) && isset($_POST['id']) && isset($_POST['update'])) {

	//If they post a title/content and there is an id set then update the page
	$updatePage = Page::updatePage($_POST['title'],$_POST['content'],$_POST['id']);
}
if(isset($_GET['id'])) {
	//If page id is set then get the content to populate the page
	$q = Core::getInstance()->dbh->prepare("SELECT * FROM `pages` WHERE `id` = ?");
	$q->execute(array($_GET['id']));
	$pageData = $q->fetch();
}

if(isset($_SESSION['session']['admin']) && $_SESSION['session']['admin'] == 1) : ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Create Page - CMS</title>
	<base href="<?php echo Config::read('baseUrl') ?>/" />
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
	<script src="resources/scripts/jquery-1.10.1.min.js"></script>
	<script src="resources/scripts/tinymce/tinymce.min.js"></script>
	<script>
		tinymce.init({
		    selector: "textarea"
		 });
	</script>
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
			<div class="clear"></div>
			<?php if($eStr): ?><div id="error"><?php echo $eStr; ?></div><?php endif; ?>
			<div id="titleHolder">
				<h2 class="title update"><?php echo isset($pageData['title']) ? 'Editing '.$pageData['title'] : 'Create a new page';?></h2>
			</div>
			<form method="post" id="newPage">
				<div class="item">
					<label>Page Title</label>
					<div class="clear"></div>
					<input type="text" value="<?php echo isset($pageData['title']) ? $pageData['title'] : '';?>" name="title" class="input" />
					<div class="err">Please enter a page title</div>
				</div>
				<div class="clear"></div>
				<label>Page Content</label>
				<div class="clear"></div>
				<textarea name="content" id="content"><?php echo isset($pageData['content']) ? $pageData['content'] : '';?></textarea>
				<div class="clear"></div>
				<input type="hidden" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '';?>" name="pageId" />
				<input type="submit" value="Submit" name="submit" /><div id="success" class="page">Page updated successfully</div>
				<div class="clear"></div>
			</form>
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
					tinymce.triggerSave();
					e.preventDefault();
					c = 0; //Error count for validation
					//Loop through input\'s to check they aren\'t empty, if so fade in the error message
					$("form input.input").each(function() {
						if($(this).val() == '') {
							c+=1;
							$(this).parent().children(".err").fadeIn("slow");
						} else {
							$(this).parent().children(".err").fadeOut("slow");
						}
					});
					if(c == 0) {
						//Send an ajax post
						$.ajax({
							type: "POST",
							url: "admin/page",
							data: {title:$("input[type=text]").val(), content:$("textarea").val(), id:$("input[type=hidden]").val(),update:1},
							complete:function(msg) {
								$("h2").fadeOut(1000, function() {
									$(this).html('Editing '+$("input[type=text]").val()).fadeIn(2000);
								});
								$("#success").fadeIn(1000).delay(2000).fadeOut(2000);
							}
						});
					}
				});
			</script>
		</div>
	</div>
</body>
</html>
<?php else : header('Location:'.Config::read('baseUrl').'/admin'); endif;
$loadTime = microtime()-$start;
echo 'Page generated in: '.$loadTime.'s';?>
