<?php
if(!isset($_GET['opentokHelper']) || $_GET['opentokHelper'] != 'fhg78y4h843hg43') {
	echo json_encode([]);
	die();
}

require_once('vendor/autoload.php');

use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;


function opentok_create_session($params) {
	/*$apiKey = '47375991';
	$apiSecret = '3a12feb462eb2d10a7623e34e31163450a0a7c3a';*/
	$apiKey = $params['apiKey'];
	$apiSecret = $params['apiSecret'];
	$opentok = new OpenTok($apiKey, $apiSecret);
	$session = $opentok->createSession(array( 'location' => '12.34.56.78' ));
	$sessionId = $session->getSessionId();
	$token = $opentok->generateToken($sessionId);
	$token = $session->generateToken();
	$token = $session->generateToken(array(
	    'role'       => Role::MODERATOR,
	    'expireTime' => time()+(7 * 24 * 60 * 60),
	    'data'       => 'name=Model',
	    'initialLayoutClassList' => array('focus')
	));

	return ['sessionId' => $sessionId, 'token' => $token];
}


$action = $_POST['action'];

if($action == 'create_session') {
	$request = $_POST['request'];
	echo json_encode(opentok_create_session(['apiKey' => $request['apiKey'], 'apiSecret' => $request['apiSecret']]));
	die();
}




?>