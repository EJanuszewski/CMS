<?php
if( !is_file('classes/config.class.php') ) {
	header('Location: install.php');
	return;
}

require_once('classes/config.class.php');
require_once('classes/core.class.php');
require_once('classes/page.class.php');

//Initialize the variables we're about to use
$content = '';

//Check for the page string
if(isset($_GET['page'])) {
	$q = Core::getInstance()->dbh->prepare('SELECT * FROM `pages` WHERE `url` = "'.$_GET['page'].'"');
	$q->execute();
	$r = $q->fetch(PDO::FETCH_ASSOC);
	//Fetch the template and put the content in it
	$template = Page::getTemplateById($r['template']);
	$content = str_replace("{CONTENT}", $r['content'], $template['content']);
}
echo $content;
?>
