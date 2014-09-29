<?php /* index.php ( forkort implementation ) */

require_once 'includes/conf.php'; // <- site-specific settings
require_once 'includes/forkort.php'; // <- forkort class file

$forkort = new forkort();
$msg = '';


// if the form has been submitted
if ( isset($_POST['longurl']) )
{
	// escape bad characters from the user's url
	$longurl = trim(mysql_escape_string($_POST['longurl']));

	// get the ip
	$ip = $_SERVER["REMOTE_ADDR"];
	
	// set the protocol to not ok by default
	$protocol_ok = false;
	
	// if there's a list of allowed protocols, 
	// check to make sure that the user's url uses one of them
	if ( count($allowed_protocols) )
	{
		foreach ( $allowed_protocols as $ap )
		{
			if ( strtolower(substr($longurl, 0, strlen($ap))) == strtolower($ap) )
			{
				$protocol_ok = true;
				break;
			}
		}
	}
	else // if there's no protocol list, screw all that
	{
		$protocol_ok = true;
	}
		
	// add the url to the database
	if ( $protocol_ok && $forkort->add_url($longurl,$ip) )
	{
		if ( REWRITE ) // mod_rewrite style link
		{
			$url = 'http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF']).'/'.$forkort->get_id($longurl);
		}
		else // regular GET style link
		{
			$url = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'].'?id='.$forkort->get_id($longurl);
		}

		$msg = '<p class="success"><img src="images/success.png" alt="" /> Din forkort&#180; URL er: <input type="text" name="copyurl" value='.$url.' /> </p>';
	}
	elseif ( !$protocol_ok )
	{
		$msg = '<p class="error"><img src="images/error.png" alt="" /> Oops! Den URL trenger en prefiks. Du vet, http://</p>';
	}
	else
	{
		$msg = '<p class="error">Creation of your forkort&#180; URL feilet for some reason.</p>';
	}
}
else // if the form hasn't been submitted, look for an id to redirect to
{
	if ( isSet($_GET['id']) ) // check GET first
	{
		$id = mysql_escape_string($_GET['id']);
	}
	elseif ( REWRITE ) // check the URI if we're using mod_rewrite
	{
		$explodo = explode('/', $_SERVER['REQUEST_URI']);
		$id = mysql_escape_string($explodo[count($explodo)-1]);
	}
	else // otherwise, just make it empty
	{
		$id = '';
	}
	
	// if the id isn't empty and it's not this file, redirect to it's url
	if ( $id != '' && $id != basename($_SERVER['PHP_SELF']) )
	{
		$location = $forkort->get_url($id);
		
		if ( $location != -1 )
		{
			header('Location: '.$location);
		}
		else
		{
			$msg = '<p class="error">Beklager, men den den forkort&#180; URL finnes ikke i v&aring; database.</p>';
		}
	}
}

// print the form
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

	<head>
		<title><?php echo PAGE_TITLE; ?></title>
		
		<meta name="CHANGEFREQ" content="daily" />
		<meta name="PRIORITY" content="1.000000" />
		<meta name="categories" content="homepage" />
		<meta name="description" content="Forkort de lange nettadressene med gratis hjelp fra forkort. Glimrende for bruk med Twitter og mye mer." />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="robots" content="all" />
		<link rel="shortcut icon" href="images/forkort.ico" type="image/x-icon" />
		<link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />
		<!--[if IE 6]><link rel="stylesheet" type="text/css" media="screen" href="css/ie6.css"  /><![endif]-->
		

	</head>
	
	<body onload="document.getElementById('longurl').focus()">
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

		<form id="boxed" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
				<input class="longurl" type="text" name="longurl" id="longurl" />
				<input class="big_button" type="submit" name="submit" id="submit" value="forkort" />
		</form> 
		 </div>
    
           
<div class="bottom_img"></div>
		<div class="bookmarklet">
			Dra denne lenken <a class="roundButton" href="javascript:void(location.href='http://forkort.no/index.php?id='+encodeURIComponent(location.href))" title="forkort.no" onclick="return false">forkort</a> til bokmerkene dine for raskere tilgang. | <a href="/api/doc.html">API</a> Dokumentasjon<br />
		</div>
		<div class="numofrecentsFront">
			<span>Vis siste </span>

			<form id="recentsformFront" method="post" action="recents.php">
				<select name="recentNumber">
				<option value="10">10</option>
				<option value="25">25</option>
				<option value="50">50</option>
				<option value="75">75</option>
				<option value="100">100</option>
				<option value="200">200</option>
				<option value="500">500</option>
				</select>
				<input class="big_button" type="submit" value="Vis" name="submit" />
			</form>
			<div class="clearer"></div>
		</div>

	
		<div class="msgContainer">
				<?php echo $msg; ?>
		</div>
		
		 <div id="footer">
		 Vi forbeholder oss retten til å slette lenker som kan virke støtende og til å stenge ute IP-adresser som misbruker denne tjenesten.<br /><br />
		   utviklet av <a href="http://dogme.no">dogme</a>
		
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

</html>
		
