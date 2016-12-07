<?php
// Representatives
$rep = array
(
	// Name	=>	Password
	'admin'	=>	'pass'
);

// HTTP Authentication
if(!isset($_SERVER['PHP_AUTH_USER']) || !isset($rep[$_SERVER['PHP_AUTH_USER']]) || $rep[$_SERVER['PHP_AUTH_USER']] != $_SERVER['PHP_AUTH_PW'])
{
	header('WWW-Authenticate: Basic realm="Live support"');
	header('HTTP/1.0 401 Unauthorized');
	
	die('Unauthorized access.');
}

// SquareLC
require '../../SquareLC/SquareLC.php';

// Clients container
$clients = array();

// Find all channels with the support_ prefix
$channels = SquareLC::channels('support_*');

foreach($channels as $name=>$activity)
{
	// Select the channel
	SquareLC::channel($name);
	
	// Get online users
	$online = SquareLC::online(true);
	
	// If no-one is online, delete the channel
	if(!$online)
	{
		unset($channels[$name]);
		
		SquareLC::close_channel();
		
		continue;
	}
	
	// If another representative is already in the chat, skip the channel
	if(count($online) > 1 && !in_array(SquareLC::session(), $online))
	{
		unset($channels[$name]);
		
		continue;
	}
	
	// Find the client
	$client[$name] = false;
	
	foreach($online as $id)
	{
		// Skip yourself
		if($id === SquareLC::session())
		{
			continue;
		}
		
		$client[$name] = SquareLC::user($id);
		
		// Check if it's not a representative
		if($client[$name]['level'] != '0')
		{
			$client[$name] = false;
		} else
		{
			break;
		}
	}
	
	// If there is no client online, skip the channel
	if(!$client[$name])
	{
		unset($channels[$name]);
		
		SquareLC::close_channel();
		
		continue;
	}
}

// If there are no channels, no-one is requesting live support
if(!$channels)
{
	die('No live support request.');
}

// Sort the channels by activity
asort($channels);

// Select the channel that has been waiting the longest
$channels = array_keys($channels);

SquareLC::channel($channels[0]);

// Set user data in the channel
SquareLC::user('session', array
(
	'@lock'		=>	true,
	'@name'		=>	$_SERVER['PHP_AUTH_USER'],
	'@level'	=>	5
));

// Output
SquareLC::chat(array
(
	'title'			=>	'Live support',
	'welcome'		=>	'You are chatting with '.$client[SquareLC::channel()]['ip'].'.',
	
	'fullscreen'	=>	true
));