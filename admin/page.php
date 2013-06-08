<?php
$start = microtime();
require_once('../classes/config.class.php');
require_once('../classes/core.class.php');
require_once('../classes/core.layout.class.php');
require_once('../classes/page.class.php');
$core = Core::getInstance();
$eStr = ''; //Error string
$adminUser = '';
$templates = '';
if(isset(Config::$confArray['baseUrl']) && Config::$confArray['baseUrl'] != '{BASEURL}') $baseUrl = Config::$confArray['baseUrl'];
session_start();
if(isset($_GET['logout'])) {
	Core::logout();
}
if(Core::isLoggedIn() == true) :
if(isset($_GET['id'])) {
	//If page id is set then get the content to populate the page
	$q = Core::getInstance()->dbh->prepare("SELECT * FROM `pages` WHERE `id` = ?");
	$q->execute(array($_GET['id']));
	$pageData = $q->fetch();
}
//Get the templates and build the dropdown list
$q = Core::getInstance()->dbh->prepare("SELECT * FROM `templates`");
$q->execute();
$templateArr = $q->fetchAll();
foreach($templateArr as $key=>$row) {
	$templates .= '<option value="'.$row['id'].'"'.((isset($_GET['id']) && $pageData['template'] == $row['id']) ? ' selected="selected"' : '').'>'.$row['title'].'</div>';
}
CoreLayout::buildHeader(array("jquery","tinymce"),"Create/Edit Page"); ?>
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
					<input id="title" type="text" value="<?php echo isset($pageData['title']) ? $pageData['title'] : '';?>" name="title" class="input" />
					<div class="err">Please enter a page title</div>
				</div>
				<div class="item">
					<label>Page URL</label>
					<div class="clear"></div>
					<input id="url" type="text" value="<?php echo isset($pageData['url']) ? $pageData['url'] : '';?>" name="url" class="input" />
					<div class="err">Please enter a page URL</div>
				</div>
				<div class="item">
					<label>Template</label>
					<div class="clear"></div>
					<select name="template">
						<?php echo $templates; ?>
					</select>
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
				$("input#title").keyup(function() {
					//Check theres not already a URL value if editing a page
					//Turn to lowercase and replace spaces with, regex taken from @http://www.coderrific.com/
					$("input#url").val($("input#title").val().toLowerCase().replace(/ +/g,'-').replace(/[0-9]/g,'').replace(/[^a-z0-9-_]/g,'').trim());
				})
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
							url: "admin/ajax?action=page",
							data: {title:$("input#title").val(), content:$("textarea").val(), template:$("select").val(), url:$("input#url").val()<?php echo (isset($_GET['id'])) ? ', id:$("input[type=hidden]").val(),update:1' : '';?>},
							success:function(msg) {
								console.log(msg);
								if(msg) {
									window.location.replace("<?php echo Config::$confArray['baseUrl']; ?>"+"/admin/page/"+msg);
								} else {
									$("h2").fadeOut(1000, function() {
										$(this).html('Editing '+$("input[type=text]").val()).fadeIn(2000);
									});
									$("#success").fadeIn(1000).delay(2000).fadeOut(2000);
								}
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
