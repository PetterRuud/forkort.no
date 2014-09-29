<?php /* index.php ( forkort implementation ) */

require_once '../includes/conf.php'; // <- site-specific settings
require_once '../includes/forkort.php'; // <- forkort class file

$forkort = new forkort();
$msg = '';
$id = NULL;
if( FALSE === stripos( $_SERVER[ 'REQUEST_METHOD' ], 'GET' ) && FALSE === stripos( $_SERVER[ 'REQUEST_METHOD' ], 'POST' ) ) {
	header( 'HTTP/1.1 405 Method Not Allowed', true, 405 );
	header( 'Content-Type: text/plain', true, 405 );
	echo 'ERROR: Will only respond to GET- or POST-requests (', $_SERVER[ 'REQUEST_METHOD' ], ').';
	exit;
}
$id = ${'_'.$_SERVER['REQUEST_METHOD']}[ 'id' ];
if(NULL == $id || strlen($id) < 1) {
	header('HTTP/1.1 400 Bad Request', true, 400 );
	header('Content-Type: text/plain', true, 400 );
	echo 'ERROR: The parameter \'id\' must be set to fetch an URL.';
	exit;
} else {
	$location = $forkort->get_url ( mysql_escape_string( $id ) );
	if( -1 === $location ) {
		header( 'HTTP/1.1 404 Not Found', true, 404 );
		header( 'Content-Type: text/plain', true, 404 );
		echo 'ERROR: Could not find the given id (\''.$id.'\').';
	} else {
		header( 'HTTP/1.1 200 OK', true, 200 );
		header( 'Content-Type: text/plain', true, 200 );
		echo $location;
	}
	exit;
}