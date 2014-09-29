<?php /* index.php ( forkort implementation ) */

require_once '../includes/conf.php'; // <- site-specific settings
require_once '../includes/forkort.php'; // <- forkort class file

$forkort = new forkort();
$msg = '';
$url = NULL;
if( FALSE === stripos( $_SERVER[ 'REQUEST_METHOD' ], 'POST' ) ) {
	header( 'HTTP/1.1 405 Method Not Allowed', true, 405 );
	header( 'Content-Type: text/plain', true, 405 );
	echo 'ERROR: Will only respond POST-requests (was ', $_SERVER[ 'REQUEST_METHOD' ], ').';
	exit;
}
$url = $_POST[ 'url' ];
if( NULL == $url || strlen( $url ) < 10 ) {
	header( 'HTTP/1.1 400 Bad Request', true, 400 );
	header( 'Content-Type: text/plain', true, 400 );
	echo 'ERROR: The parameter \'url\' was invalid.';
	exit;
}

if( $url ) {
	$protocol_ok = false;
	foreach ( $allowed_protocols as $ap ) {
		if ( strtolower( substr( $longurl, 0, strlen( $ap ) ) ) == strtolower( $ap ) ) {
			$protocol_ok = true;
			break;
		}
	}
	if( FALSE === $protocol_ok ) {
		header( 'HTTP/1.1 400 Bad Request', true, 400 );
		header( 'Content-Type: text/plain', true, 400 );
		echo 'ERROR: Only supports ftp-, http-, https- and mailto-links.';
		exit;
	}
	
	$stat = $forkort->add_url( mysql_escape_string( $url ), $_SERVER['REMOTE_ADDR'] );
	if( is_string( $stat ) ) {
		header( 'HTTP/1.1 200 OK', true, 200 );
		header( 'Content-Type: text/plain', true, 200 );
		echo $stat;
	} else if ( TRUE === $stat ) {
		header( 'HTTP/1.1 302 Found', true, 302 );
		header( 'Location: http://forkort.no/api/get.php?id=' . $forkort->get_id( mysql_escape_string( $url ) ), true, 302 );
	} else {
		header( 'HTTP/1.1 404 Not Found', true, 404 );
		header( 'Content-Type: text/plain', true, 404 );
		echo 'ERROR: Could not create id for the url (\'' . $url . '\').';
	}
}