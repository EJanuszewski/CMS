<?php
class Page {
	
	//Takes the page title and the content
	public static function newPage($title,$content,$template,$url) {
		//Perform SQL to insert the page
        $q = Core::getInstance()->dbh->prepare('INSERT INTO `pages` (`title`,`content`,`template`,`url`) VALUES (?,?,?,?)');
        $q->execute(array($title,$content,$template,$url));
		return Core::getInstance()->dbh->lastInsertId();
        
	}

	//Takes the page title, content and id then updates it
	public static function updatePage($title,$content,$template,$url,$id) {
		//Perform SQL to update the page
		$q = Core::getInstance()->dbh->prepare("UPDATE `pages` SET `title` = ?,`content` = ?,`template` = ?,`url` = ? where `id` = ? LIMIT 1");
		if($q->execute(array($title,$content,$template,$url,$id))) return true;
	}

	//Gets a list of the pages and the titles from the database
	public static function getPageList($pageList = '') {
		//SQL to get the list
		$q = Core::getInstance()->dbh->prepare("SELECT * FROM `pages`");;
		$q->execute();
		$pages = $q->fetchAll();
		foreach ($pages as $key => $value) {
			$pageList .= '<li><a href="'.Config::$confArray['baseUrl'].'/admin/page/'.$value['id'].'">'.$value['title'].'</a></li>';
		}
		echo '<ul id="pageList">
				'.$pageList.'
			</ul>';
	}

	//Gets a list of the templates and the titles from the database
	public static function getTemplateList($templateList = '') {
		//SQL to get the list
		$q = Core::getInstance()->dbh->prepare("SELECT * FROM `templates`");;
		$q->execute();
		$templates = $q->fetchAll();
		foreach ($templates as $key => $value) {
			$templateList .= '<li><a href="'.Config::$confArray['baseUrl'].'/admin/template/'.$value['id'].'">'.$value['title'].'</a></li>';
		}
		echo '<ul id="pageList">
				'.$templateList.'
			</ul>';
	}

	//Takes the template title and the content
	public static function newTemplate($title,$content) {
		//Perform SQL to insert the page
        $q = Core::getInstance()->dbh->prepare('INSERT INTO `templates` (`title`,`content`) VALUES (?,?)');
        $q->execute(array($title,$content));
		return Core::getInstance()->dbh->lastInsertId();
        
	}

	//Takes the template title, content and id then updates it
	public static function updateTemplate($title,$content,$id) {
		//Perform SQL to update the page
		$q = Core::getInstance()->dbh->prepare("UPDATE `templates` SET `title` = ?,`content` = ? where `id` = ? LIMIT 1");
		if($q->execute(array($title,$content,$id))) return true;
	}

	//Gets the template contents by id
	public static function getTemplateById($id) {
		$q = Core::getInstance()->dbh->prepare("SELECT `content` FROM `templates` WHERE `id`= ?");
		$q->execute(array($id));
		$content = $q->fetch(PDO::FETCH_ASSOC);
		return $content;
	}

}
?>