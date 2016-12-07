<?php
return array
(
	'censor'			=>	array
	(
		// These words will be replaced by asterisks (*)
		'fuck', 'shit', 'cock', 'cunt', 'bitch', 'dick'
	),
	
	'noscript'			=>	'You must enable javascript to take part in the chat.',
	'nocookie'			=>	'You must enable cookies to take part in the chat.',
	
	'welcome'			=>	'Welcome to live chat! Type /help to see a list of commands.',
	'time_out'			=>	'The connection has timed out. Please refresh the page.',
	
	'alphanumeric'		=>	'',
	'default_nickname'	=>	'anon',
	
	'or'				=>	'or',
	'send'				=>	'send',
	
	'room_full'			=>	'The chat room is full.',
	'online_users'		=>	array
	(
		1	=>	'1 person online',
		'n'	=>	'{n} people online'
	),
	
	// Spam
	'too_many_messages'	=>	'You are sending too many messages. Please wait a short while.',
	
	// Commands
	'command_list'		=>	'Type /help [command] to learn more about a specific command.',
	'command_not_found'	=>	'Command not found. Type /help to see a list of commands.',
	'command_format'	=>	'That is the wrong usage of the command. Type /help {command} for help.',
	
	'commands'		=>	array
	(
		'help'		=>	'See a list of all commands.',
		'nickname'	=>	'Change your nickname.',
		'whisper'	=>	'Send a private message.',
		'emotes'	=>	'See a list of emoticons.',
		'mute'		=>	'Prevent a user from sending messages.',
		'unmute'	=>	'Unmute a user.',
		'ban'		=>	"Restrict a user's access to chat.",
		'unban'		=>	'Unban a user.',
		'set'		=>	'Set a user variable.',
		'get'		=>	'Get a user variable.',
		'close'		=>	'Close the channel.'
	),
	
	'command_args'		=>	'Arguments in <arrows> are required, arguments in [brackets] are optional.',
	'command_usage'		=>	'Usage: {command}',
	
	'commands_help'		=>	array
	(
		'help'			=>	array
		(
			'format'	=>	'[command]',
			'command'	=>	'Command name.'
		),
		'nickname'		=>	array
		(
			'format'	=>	'<name> [password]',
			'name'		=>	'What to set your nickname to.',
			'password'	=>	'The password, if required.'
		),
		'whisper'		=>	array
		(
			'format'	=>	'<name> <message>',
			'name'		=>	'Who to whisper to.',
			'message'	=>	'The private message.'
		),
		'emotes'		=>	array
		(
			'format'	=>	''
		),
		'mute'			=>	array
		(
			'format'	=>	'<name> [time]',
			'name'		=>	'The name of the user to mute.',
			'time'		=>	'For how long to mute the user (n days/n hours/n minutes/etc).'
		),
		'unmute'		=>	array
		(
			'format'	=>	'<name>',
			'name'		=>	'The name of the user to unmute'
		),
		'ban'			=>	array
		(
			'format'	=>	'<name> [time]',
			'name'		=>	'The name of the user to ban.',
			'time'		=>	'For how long to ban the user (n days/n hours/n minutes/etc).'
		),
		'unban'			=>	array
		(
			'format'	=>	'<name>',
			'name'		=>	'The name of the user to unban'
		),
		'set'			=>	array
		(
			'format'	=>	'<name> <key> <value>',
			'name'		=>	'The name of the user to modify.',
			'key'		=>	'The name of the variable.',
			'value'		=>	'The value of the variable.'
		),
		'get'			=>	array
		(
			'format'	=>	'<name> <key>',
			'name'		=>	'The name of the user.',
			'key'		=>	'The name of the variable to get.'
		),
		'close'			=>	array
		(
			'format'	=>	''
		)
	),
	
	// Time period
	'time_periods'		=>	array
	(
		// x second 	=>	keywords
		1				=>	array('s', 'sec', 'second', 'seconds'),
		60				=>	array('m', 'min', 'minute', 'minutes'),
		3600			=>	array('h', 'hr', 'hour', 'hours'),
		86400			=>	array('d', 'day', 'days'),
		604800			=>	array('w', 'week', 'weeks'),
		604800*4		=>	array('m', 'month', 'months'),
		604800*4*12		=>	array('y', 'yr', 'year', 'years')
	),
	
	// Users
	'user_you'			=>	'You',
	
	'user_join'			=>	'{user} has joined the chat.',
	'user_leave'		=>	'{user} has left the chat.',
	
	'nickname_already'		=>	'That is already your nickname.',
	'nickname_length'		=>	'Your nickname must be {min} to {max} characters in length.',
	'nickname_alphanumeric'	=>	'Your nickname must be alphanumeric.',
	'nickname_in_use'		=>	'Someone is already using that nickname.',
	'nickname_no_pass'		=>	'That nickname requires a password.',
	'nickname_wrong_pass'	=>	'Incorrect password.',
	
	'nickname_change'		=>	'{user} changed his/her nickname to {new}.',
	
	'nickname_not_found'	=>	'There is no user by that nickname in the chat.',
	
	// Whisper
	'cannot_whisper_self'	=>	"You can't whisper to yourself!",
	
	'action_whisper'		=>	'whispers',
	
	// Mute
	'cannot_mute'		=>	"You can't mute that user!",
	'cannot_mute_self'	=>	"You can't mute yourself!",
	
	'user_not_muted'	=>	'That user is not muted.',
	
	'you_are_muted'		=>	'You are muted!',
	'user_is_muted'		=>	'{user} is still muted!',
	
	'action_mute_by'	=>	'{user} has been muted by {moderator}.',
	'action_mute_time'	=>	'{user} has been muted by {moderator} until {time}.',
	
	'action_unmute'		=>	'{user} has been unmuted.',
	'action_unmute_by'	=>	'{user} has been unmuted by {moderator}.',
	
	// Ban
	'cannot_ban'		=>	"You can't ban that user!",
	'cannot_ban_self'	=>	"You can't ban yourself!",
	
	'user_not_banned'	=>	'That user is not banned.',
	
	'you_are_banned'	=>	'You have been banned from live chat.',
	
	'action_ban_by'		=>	'{user} has been banned by {moderator}.',
	'action_ban_time'	=>	'{user} has been banned by {moderator} until {time}.',
	
	'action_unban'		=>	'{user} has been unbanned.',
	'action_unban_by'	=>	'{user} has been unbanned by {moderator}.',
);