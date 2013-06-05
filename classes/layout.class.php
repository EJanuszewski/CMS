<?php

class CoreLayout() {
	
	public static function getNav() {

		$nav = '<ul>
					<li><a href="#">Home</a></li>
					<li><a href="#">Pages</a>
						<ul>
							<li><a href="#">Create New Page</a></li>
							<li><a href="#">Edit Pages</a></li>
						</ul>
					</li>
					<li class="logout"><a href="?logout">Logout</a></li>
				</ul>';

		return $nav;

	}

}

?>