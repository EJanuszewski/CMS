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
if(Core::isLoggedIn() == true) :
if(isset($_GET['id'])) {
	//If template id is set then get the content to populate the templates
	$q = Core::getInstance()->dbh->prepare("SELECT * FROM `templates` WHERE `id` = ?");
	$q->execute(array($_GET['id']));
	$pageData = $q->fetch();
}


CoreLayout::buildHeader(array("jquery"),"Create/Edit Template"); ?>
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
				<h2 class="title update"><?php echo isset($pageData['title']) ? 'Editing '.$pageData['title'] : 'Create a new template';?></h2>
			</div>
			<form method="post" id="newPage">
				<div class="item">
					<label>Template Title</label>
					<div class="clear"></div>
					<input type="text" value="<?php echo isset($pageData['title']) ? $pageData['title'] : '';?>" name="title" class="input" />
					<div class="err">Please enter a template title</div>
				</div>
				<div class="clear"></div>
				<div id="templateBlock">
					<label>Template Content(Use the tags below to insert a tag)</label>
					<div class="clear"></div>
					<textarea name="content" id="content"><?php echo isset($pageData['content']) ? htmlentities($pageData['content'], ENT_QUOTES, 'UTF-8') : '';?></textarea>
					<div id="templateItems">
						<div class="templateItem html">HTML</div>
						<div class="templateItem head">HEAD</div>
						<div class="templateItem body">BODY</div>
						<div class="templateItem script">SCRIPT</div>
						<div class="templateItem div">DIV</div>
						<div class="templateItem ul">UL</div>
						<div class="templateItem span">SPAN</div>
						<div class="templateItem content">CONTENT</div>
					</div>
					<script>
					//Get cursor and insert text function from Scottklarr.com
					function insertAtCaret(areaId,text) {
						var txtarea = document.getElementById(areaId);
						var scrollPos = txtarea.scrollTop;
						var strPos = 0;
						var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ? 
							"ff" : (document.selection ? "ie" : false ) );
						if (br == "ie") { 
							txtarea.focus();
							var range = document.selection.createRange();
							range.moveStart ('character', -txtarea.value.length);
							strPos = range.text.length;
						}
						else if (br == "ff") strPos = txtarea.selectionStart;

						var front = (txtarea.value).substring(0,strPos);  
						var back = (txtarea.value).substring(strPos,txtarea.value.length); 
						txtarea.value=front+text+back;
						//If tag is content then dont divide by 2, but if it is then divide by 2 so it centralises the cursor
						if(text == "{CONTENT}") {strPos = strPos + text.length;} else {strPos = strPos + text.length/2};

						if (br == "ie") { 
							txtarea.focus();
							var range = document.selection.createRange();
							range.moveStart ('character', -txtarea.value.length);
							range.moveStart ('character', strPos);
							range.moveEnd ('character', 0);
							range.select();
						}
						else if (br == "ff") {
							txtarea.selectionStart = strPos;
							txtarea.selectionEnd = strPos;
							txtarea.focus();
						}
						txtarea.scrollTop = scrollPos;
					}	

						$(".templateItem").click(function() {
							var cursor = $(this).prop("selectionStart");
							//Get the tag for whichever div they clicked
							var tag = $(this).attr('class').split(' ')[1];
							constructedTag = '<'+tag+'></'+tag+'>';
							if(tag == 'content') constructedTag = '{CONTENT}';
							insertAtCaret('content',constructedTag);
						});
					</script>
					<div class="clear"></div>
				</div>
				<input type="hidden" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '';?>" name="pageId" />
				<input type="submit" value="Submit" name="submit" /><div id="success" class="page">Template updated successfully</div>
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
							url: "admin/ajax?action=template",
							data: {title:$("input[type=text]").val(), content:$("textarea").val()<?php echo (isset($_GET['id'])) ? ', id:$("input[type=hidden]").val(),update:1' : '';?>},
							success:function(msg) {
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