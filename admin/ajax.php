<?php
session_start();
require_once('../classes/config.class.php');
require_once('../classes/core.class.php');
require_once('../classes/page.class.php');
	//Check they are logged in before doing anything
	if(Core::isLoggedIn() == true) {
		//They are logged in
		//Check which function they want to do
		switch ($_GET['action']) {
			case 'page': //Update or create a new page
				if(isset($_POST['title']) && isset($_POST['id']) == false) {
					//If they post a title then add a new page
					$returnId = Page::newPage($_POST['title'],$_POST['content'],$_POST['template'],$_POST['url']);
					echo $returnId;
				} elseif(isset($_POST['title']) && isset($_POST['content']) && isset($_POST['id']) && isset($_POST['update'])) {
					//If they post a title/content and there is an id set then update the page
					$updatePage = Page::updatePage($_POST['title'],$_POST['content'],$_POST['template'],$_POST['url'],$_POST['id']);
				}
			break;
			case 'template': //Update or create a new template
				if(isset($_POST['title']) && isset($_POST['id']) == false) {
					//If they post a title then add a new template
					$returnId = Page::newTemplate($_POST['title'],$_POST['content']);
					echo $returnId;
				} elseif(isset($_POST['title']) && isset($_POST['content']) && isset($_POST['id']) && isset($_POST['update'])) {
					//If they post a title/content and there is an id set then update the template
					$updatePage = Page::updateTemplate($_POST['title'],$_POST['content'],$_POST['id']);
				}
			break;
		}

	} else {
		header("Location:/");
	}
?>