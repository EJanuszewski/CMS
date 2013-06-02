<?php

require_once('classes/config.class.php');
require_once('classes/core.class.php');
$core = Core::getInstance();
//Check for the page string
if($_GET['page']) {
	$q = $core->dbh->prepare('SELECT * FROM `pages` WHERE `title` = "'.$_GET['page'].'"');
	$q->execute();
	$r = $q->fetchAll();
	echo $r[0]['content'];
}

?>