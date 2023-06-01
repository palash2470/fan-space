<?php
require_once('opentok/vendor/autoload.php');

use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;
use OpenTok\Session;
use OpenTok\Role;


$apiKey = '47375991';
$apiSecret = '3a12feb462eb2d10a7623e34e31163450a0a7c3a';
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

echo $token;
?>