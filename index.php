<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('classes/config.class.php');
require_once('classes/core.class.php');
require_once('classes/page.class.php');

//GOTO install directory/file if it exists
if( is_file(Config::read('setup.path')) ) {
	header('Location: ' . Config::read('setup.path'));
	return;
}


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
