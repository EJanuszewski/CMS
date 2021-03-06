<?php
class Page {
	
	//Takes the page title and the content
	public static function newPage($title,$content) {
		//Perform SQL to insert the page
        $q = Core::getInstance()->dbh->prepare('INSERT INTO `pages` (`title`,`content`) VALUES (?,?)');
        $q->execute(array($title,$content));
		return Core::getInstance()->dbh->lastInsertId();
        
	}

	//Takes the page title, content and id then updates it
	public static function updatePage($title,$content,$id) {
		//Perform SQL to update the page
		$q = Core::getInstance()->dbh->prepare("UPDATE `pages` SET `title` = ?,`content` = ? where `id` = ? LIMIT 1");
		if($q->execute(array($title,$content,$id))) return true;
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

}
?>