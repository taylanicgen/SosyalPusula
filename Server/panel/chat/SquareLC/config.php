<?php
return array
(
	// Read the documentation to better understand how to set the values in this file
	'secret'		=>	'',
	'tracking'		=>	'cookie',
	
	'log'			=>	true,
	
	'lang'			=>	'en',
	'style'			=>	'default',
	
	// Path
	'static_path'	=>	'http://www.aryasismakina.com/madmin/chat/SquareLC/',
	'profile_path'	=>	false,
	
	// Loading
	'load_lines'	=>	1000,
	'load_online'	=>	5000,
	'send_timeout'	=>	500,
	
	// Limit
	'max_users'				=>	false,
	'msg_max_length'		=>	100,
	
	'name_min_length'		=>	3,
	'name_max_length'		=>	16,
	'name_case_sensitive'	=>	false,
	
	// Cookies
	'cookie_path'	=>	'/',
	'cookie_domain'	=>	'',
	
	// Commands
	'commands'		=>	array
	(
		'message'	=>	0,
		'help'		=>	0,
		'nickname'	=>	0,
		'whisper'	=>	0,
		'emotes'	=>	0, // Set this to false to disable emoticons
		'mute'		=>	1,
		'unmute'	=>	1,
		'ban'		=>	3,
		'unban'		=>	3,
		'set'		=>	true,
		'get'		=>	true,
		'close'		=>	true
	),
	
	// Emoticons
	'emoticons'		=>	array
	(
		// Filename		Shortcuts
		'smile'		=>	array(':-)', ':)'),
		'sad'		=>	array(':-(', ':('),
		'grin'		=>	array(':D', 'xD'),
		'surprised'	=>	array(':-O', ':O'),
		'tongue'	=>	array(':-P', ':P')
	),
	
	// Users
	'users'			=>	array
	(
		// name		=>	array(password, level)
		'moderator'	=>	array('pass', 1),
		'admin'		=>	array('pass', 3)
	),
	
	// Channels
	'channels'		=>	array
	(
		'support'	=>	array
		(
			'log'		=>	true,
			'commands'	=>	array
			(
				'set'	=>	5,
				'get'	=>	5,
				'close'	=>	5
			)
		)
	)
);