<?php /* conf.php ( config file ) */

// page title
define('PAGE_TITLE', 'forkort');
// page url without prefix and trailing slash

define('PAGE_URL', 'forkort.no');

// MySQL connection info
define('MYSQL_USER', '***');
define('MYSQL_PASS', '***');
define('MYSQL_DB', '***');
define('MYSQL_HOST', '***');

// MySQL tables
define('URL_TABLE', 'forkort');

// use mod_rewrite?
define('REWRITE', true);

// allow urls that begin with these strings
$allowed_protocols = array('http:', 'https:', 'mailto:');

// uncomment the line below to skip the protocol check
// $allowed_procotols = array();

?>
