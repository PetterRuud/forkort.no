<?php
require_once 'includes/conf.php'; // <- site-specific settings
require_once 'includes/forkort.php'; // <- forkort class file_exists()

$forkort = new forkort();



if ( isset($_POST['recentNumber']) ) {

$recentNumber = mysql_escape_string($_POST['recentNumber']);

	foreach($forkort->show_recents($recentNumber) as $recent) {
		$recentUrl[] = "<div class=\"frktURL\"> 
		<strong><a href=\"http://".PAGE_URL."/".$recent['id']."\" target=\"_blank\" title=\"".$recent['url']."\">".PAGE_URL."/".$recent['id']."</a></strong>
		</div> 
		<div class=\"longURL\">".$recent['url']."</div>";
	}


}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">  
<head>
	<title><?php echo PAGE_TITLE; ?> - Siste</title>
	<meta name="CHANGEFREQ" content="daily" />
	<meta name="PRIORITY" content="1.000000" />
	<meta name="description" content="Forkort de lange nettadressene med gratis hjelp fra forkort. Glimrende for bruk med Twitter og mye mer." />
			<link rel="shortcut icon" href="images/forkort.ico" type="image/x-icon" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="robots" content="all" />

	
	<link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
	<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="css/ie6.css"  /><![endif]-->
			
	
</head>

	<div id="container">
  <div id="header_container">
                <div id="header">
                <div class="big_button">
				<a href="index.php" title="<?php echo PAGE_TITLE; ?>.no"><?php echo PAGE_TITLE; ?>.no</a>
				</div>
			</div>
            </div>
		<div class="top_img"></div>
		<div id="content">
		<div id="boxed">				
		<form id="recentsform" method="post" action="">
		<select name="recentNumber">
		<option value="10">10</option>
		<option value="25">25</option>
		<option value="50">50</option>
		<option value="75">75</option>
		<option value="100">100</option>
		<option value="200">200</option>
		<option value="500">500</option>
		</select>
		<input class="big_button" type="submit" value="Oppfrisk" name="submit" />
		</form>
		</div>


		<div class="clearer"></div>
		<div class="urlcontainer">
		<?php foreach($recentUrl as $recents) {echo $recents;} ?>
		<div class="clearer">
		</div>

		</div>

		</div>
		<div class="bottom_img"></div>		          
		   <div id="footer">
		   <a href="http://<?php echo PAGE_URL; ?>"> Tilbake til <?php echo PAGE_TITLE; ?>.no</a>
			</div>
            </div>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-8454552-2");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
