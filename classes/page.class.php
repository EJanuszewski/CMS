<?php
class Page {
	
	//Takes the page title and the content
	public static function newPage($title,$content) {
		//Perform SQL to insert the page
        $q = Core::getInstance()->dbh->prepare('INSERT INTO `pages` (`title`,`content`) VALUES (?,?)');
        $q->execute(array($title,$content));
        
	}

}
?>