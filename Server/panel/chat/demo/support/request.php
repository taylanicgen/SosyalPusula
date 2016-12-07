<?php
require '../../SquareLC/SquareLC.php';

// Prefix the channel name with support_ so it's easier to filter
session_start();

$channel = 'support_'.sha1(session_id());

SquareLC::init($channel, true);

// Don't allow the user to request live support if they're banned
if(isset(SquareLC::$user['ban']))
{
	die('You have been banned from live support.');
}

// Set user data in the channel
SquareLC::user('session', array
(
	'@lock'		=>	true,
	'@name'		=>	'Client',
	'@level'	=>	0,
	
	'@ip'		=>	$_SERVER['REMOTE_ADDR']
));

// Output
SquareLC::chat(array
(
	'title'			=>	'Live support',
	'welcome'		=>	'Welcome to live support! Please wait while a representative joins the chat...',
	
	'fullscreen'	=>	true
));