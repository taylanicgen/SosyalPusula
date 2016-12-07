<?php
abstract class SquareLC
{
	// Constants
	const DELIMITER = "\t";
	
	// Variables
	public static
		$user = array(),
		$config = array(),
		$channel = false;
	
	// Initialize
	public static function init($channel='global', $validate=false)
	{
		self::$channel = $channel;
		
		if(self::$config)
		{
			return self::$config;
		}
		
		// Configuration
		$config_file = dirname(__file__).DIRECTORY_SEPARATOR.'config.php';
		
		if($validate && !file_exists($config_file))
		{
			trigger_error('Please rename _config.php to config.php in the SquareLC folder and set your settings in that file.', E_USER_ERROR);
		}
		
		self::$config = include $config_file;
		
		// Validate
		if($validate)
		{
			// Language
			if(!file_exists(dirname(__file__).DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.self::$config['lang'].'.php'))
			{
				trigger_error("Language file for language ".self::config('lang')." wasn't found in SquareLC/lang/ folder.", E_USER_ERROR);
			}
			
			// Database
			if(!is_writable(dirname(__file__).DIRECTORY_SEPARATOR.'db'))
			{
				trigger_error("The db folder in SquareLC must be writable. Try chmodding it to 0755 or 0777", E_USER_ERROR);
			}
		}
		
		self::user('session');
		
		return self::$config;
	}
	
	// Configuration
	public static function config($key)
	{
		$channel = substr(self::$channel, 0, strpos(self::$channel.'_', '_'));
		
		if(isset(self::$config['channels'][$channel]) && isset(self::$config['channels'][$channel][$key]))
		{
			return is_array(self::$config['channels'][$channel][$key])?array_merge(self::$config[$key], self::$config['channels'][$channel][$key]):self::$config['channels'][$channel][$key];
		}
		
		return self::$config[$key];
	}
	
	// Path
	public static function path($folder, $file=null, $must_exist=true)
	{
		if($file === null)
		{
			$file = self::$channel;
		}
		
		$path = dirname(__file__).DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR.$folder.DIRECTORY_SEPARATOR.$file.'.csv';
		
		if($must_exist && !file_exists($path))
		{
			exit;
		}
		
		return $path;
	}
	
	// Chat
	public static function chat($settings=array(), $style=array())
	{
		$channel = isset($settings['channel'])?$settings['channel']:(self::$channel?self::$channel:'global');
		
		// Initialize
		self::init($channel, true);
		
		// Settings
		$settings = array_merge(array
		(
			'title'			=>	'Live chat',
			
			'width'			=>	'100%',
			'height'		=>	'100%',
			
			'fullscreen'	=>	false,
			
			'view_online'	=>	true,
			'outer_border'	=>	true,
			'view_send_btn'	=>	false
		), $settings);
		
		// Create
		$lines = self::path('lines', null, false);
		$online = self::path('online', null, false);
		
		if(!file_exists($lines))
		{
			file_put_contents($lines, '');
		}
		
		if(!file_exists($online))
		{
			file_put_contents($online, '');
		}
		
		// Fullscreen
		if($settings['fullscreen'])
		{
			$settings['width'] = '100%';
			$settings['height'] = '100%';
			$settings['outer_border'] = false;
			
			require dirname(__file__).DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'fullscreen.html';
			
			return '';
		}
		
		// IFrame
		require dirname(__file__).DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'iframe.html';
		
		return '';
	}
	
	// Session
	public static function session()
	{
		return self::$user['session'];
	}
	
	// User
	public static function user($raw_id='session', $write=array())
	{
		// Session
		if($raw_id == 'session')
		{
			if(isset($write['id']))
			{
				$_COOKIE['SquareLC'] = md5($write['id'].self::config('secret'));
				
				setcookie('SquareLC', $_COOKIE['SquareLC'], strtotime('+2 years'), self::config('cookie_path'), self::config('cookie_domain'));
			}
			
			if(self::config('tracking') == 'cookie')
			{
				if(isset($_COOKIE['SquareLC']))
				{
					$id = md5($_COOKIE['SquareLC']);
					
					if(!isset($write['id']) && !file_exists(self::path('users', $id, false)))
					{
						unset($_COOKIE['SquareLC'], $id);
					}
				}
				
				if(!isset($id))
				{
					$_COOKIE['SquareLC'] = md5(uniqid().self::config('secret'));
					
					setcookie('SquareLC', $_COOKIE['SquareLC'], strtotime('+2 years'), self::config('cookie_path'), self::config('cookie_domain'));
					
					$id = md5($_COOKIE['SquareLC']);
				}
			} else
			{
				$id = md5($_SERVER['REMOTE_ADDR']);
			}
		} else
		{
			$id = $raw_id;
		}
		
		// Open
		$filename = self::path('users', $id, false);
		
		if(!file_exists($filename))
		{
			if($raw_id != 'session')
			{
				exit;
			}
			
			$file = fopen($filename, 'w+');
		} else
		{
			$file = fopen($filename, 'r+');
		}
		
		// Read
		$user = array('session'=>$id);
		
		$content = '';
		$overwrite = false;
		
		while($raw = fgets($file))
		{
			$line = explode(self::DELIMITER, rtrim($raw, "\r\n"));
			$count = count($line);
			
			// Write
			if($write)
			{
				$key = false;
				
				if($count == 2 && array_key_exists($line[0], $write))
				{
					$key = $line[0];
				} elseif($count == 3 && $line[2] == self::$channel && array_key_exists('@'.$line[0], $write))
				{
					$key = '@'.$line[0];
				}
				
				if($key)
				{
					if($write[$key] === null)
					{
						$overwrite = true;
						
						unset($write[$key]);
						
						continue;
					} else
					{
						if($line[1] != $write[$key])
						{
							$overwrite = true;
							
							$line[1] = $write[$key];
						}
						
						unset($write[$key]);
					}
				}
			}
			
			$content .= implode(self::DELIMITER, $line).PHP_EOL;
			
			if(($count == 3 && $line[2] == self::$channel) || ($count == 2 && !isset($user[$line[0]])))
			{
				$user[$line[0]] = $line[1];
			}
		}
		
		// Overwrite
		if($overwrite)
		{
			rewind($file);
			
			ftruncate($file, 0);
			
			fwrite($file, $content);
		}
		
		// Write
		foreach($write as $key=>$value)
		{
			if($value !== null)
			{
				if($key{0} == '@')
				{
					$key = substr($key, 1);
					
					fwrite($file, $key.self::DELIMITER.$value.self::DELIMITER.self::$channel.PHP_EOL);
					
					if(!isset($user[$key]))
					{
						$user[$key] = $value;
					}
				} else
				{
					$user[$key] = $value;
					
					fwrite($file, $key.self::DELIMITER.$value.PHP_EOL);
				}
			}
		}
		
		// Session
		if($raw_id == 'session')
		{
			self::$user = $user;
		}
		
		// Close
		fclose($file);
		
		// Unmute
		if(isset($user['muted']) && $user['muted'] != '0' && $user['muted'] < self::ts())
		{
			self::write('unmute', array
			(
				'user'	=>	$id
			));
			
			return self::user($raw_id, array
			(
				'@muted'	=>	null
			));
		}
		
		// Unban
		if(isset($user['ban']) && $user['ban'] != '0' && $user['ban'] < self::ts())
		{
			self::write('unban', array
			(
				'user'	=>	$id
			));
			
			return self::user($raw_id, array
			(
				'ban'	=>	null
			));
		}
		
		return $user;
	}
	
	// Timestamp
	public static function ts()
	{
		$mt = explode(' ', microtime());
		
		return $mt[1].substr($mt[0], 2, 3);
	}
	
	// Channels
	public static function channels($format)
	{
		$dir = strlen(dirname(__file__).DIRECTORY_SEPARATOR.'db'.DIRECTORY_SEPARATOR.'lines'.DIRECTORY_SEPARATOR);
		$channels = array();
		
		$files = glob(self::path('lines', $format, false));
		
		foreach($files as $file)
		{
			$channels[substr($file, $dir, -4)] = filectime($file);
		}
		
		return $channels;
	}
	
	// Select channel
	public static function channel($channel=null)
	{
		if($channel !== null)
		{
			self::init($channel);
		}
		
		return self::$channel;
	}
	
	// Close channel
	public static function close_channel()
	{
		// Clear user information
		$online = self::online(true);
		
		foreach($online as $id)
		{
			$filename = self::path('users', $id);
			
			$content = '';
			
			$file = fopen($filename, 'r+');
			
			while($raw = fgets($file))
			{
				$line = explode(self::DELIMITER, rtrim($raw, "\r\n"));
				
				if(count($line) == 2 || $line[2] != self::$channel)
				{
					$content .= $raw;
				}
			}
			
			rewind($file);
			
			ftruncate($file, 0);
			
			fwrite($file, $content);
			
			fclose($file);
		}
		
		// Delete channel files
		$files = array('lines', 'online', 'log');
		
		foreach($files as $file)
		{
			$path = self::path($file);
			
			if(file_exists($path))
			{
				unlink($path);
			}
		}
	}
	
	// Online
	public static function online($return=false)
	{
		$ts = self::ts();
		$timeout = $ts - self::config('load_online')*2 - 1000;
		
		$content = '';
		$overwrite = false;
		
		$online = array();
		
		// Open
		$file = fopen(self::path('online'), 'r+');
		
		// Read
		while($raw = fgets($file))
		{
			$line = explode(self::DELIMITER, rtrim($raw, "\r\n"));
			
			if(self::$user && $line[0] == self::$user['session'])
			{
				$overwrite = true;
				
				$line[1] = $ts;
			} elseif($line[1] < $timeout)
			{
				$overwrite = true;
				
				self::write('leave', array
				(
					'session'	=>	$line[0]
				));
				
				continue;
			}
			
			$content .= $line[0].self::DELIMITER.$line[1].PHP_EOL;
			
			if($return)
			{
				$online[] = $line[0];
			}
		}
		
		// Overwrite
		if($overwrite)
		{
			rewind($file);
			
			ftruncate($file, 0);
			
			fwrite($file, $content);
		}
		
		// Close
		fclose($file);
		
		return $online;
	}
	
	// Line
	public static function line($event, $data=false, $ts=false)
	{
		if($ts === false)
		{
			$ts = self::ts();
		}
		
		if($data)
		{
			return $ts.self::DELIMITER.$event.self::DELIMITER.implode(self::DELIMITER, array_keys($data)).self::DELIMITER.implode(self::DELIMITER, $data).PHP_EOL;
		} else
		{
			return implode(self::DELIMITER, array_keys($event)).self::DELIMITER.implode(self::DELIMITER, $event).PHP_EOL;
		}
	}
	
	// Write
	public static function write($event, $data)
	{
		$ts = self::ts();
		$timeout = $ts - self::config('load_lines')*1.5;
		
		$content = '';
		
		// Open
		$file = fopen(self::path('lines'), 'r+');
		
		while($raw = fgets($file))
		{
			$line = substr($raw, 0, strlen($ts));
			
			if($line < $timeout)
			{
				continue;
			}
			
			$content .= $raw;
		}
		
		// Line
		$line = self::line($event, $data, $ts);
		
		$content .= $line;
		
		// Overwrite
		rewind($file);
		
		ftruncate($file, 0);
		
		fwrite($file, $content);
		
		// Log
		if(self::config('log'))
		{
			$log = fopen(self::path('log', null, false), 'a');
			
			fwrite($log, $line);
			
			fclose($log);
		}
		
		// Close
		fclose($file);
		
		return $content;
	}
	
	// Command
	public static function command($name, $args)
	{
		if(isset(self::$user['muted']) || isset(self::$user['ban']))
		{
			return;
		}
		
		$commands = self::config('commands');
		
		if(!isset($commands[$name]) || $commands[$name] === false || self::$user['level'] < $commands[$name])
		{
			return false;
		}
		
		return call_user_func_array(array('SquareLC', 'command_'.$name), $args);
	}
	
	// Message
	private static function command_message($message)
	{
		echo self::write('message', array
		(
			'session'	=>	self::$user['session'],
			'message'	=>	mb_substr(strtr(trim($message), "\r\n", '  '), 0, self::config('msg_max_length'))
		));
	}
	
	// Whisper
	private static function command_whisper($id, $message)
	{
		$user = self::user($id);
		
		// Self
		if($id == self::$user['session'])
		{
			exit;
		}
		
		// Write
		echo self::write('whisper', array
		(
			'to'		=>	$id,
			'session'	=>	self::$user['session'],
			'message'	=>	mb_substr(strtr(trim($message), "\r\n", '  '), 0, self::config('msg_max_length'))
		));
	}
	
	// Nickname
	private static function command_nickname($name, $password=null)
	{
		$write = array
		(
			'@name'	=>	$name
		);
		
		// Locked
		if(isset(self::$user['lock']) && self::$user['lock'] == '1')
		{
			exit;
		}
		
		// Length
		if(mb_strlen($name) < self::config('name_min_length') || mb_strlen($name) > self::config('name_max_length'))
		{
			die(self::line('error', array
			(
				'message'	=>	'nickname_length',
				'min'		=>	self::config('name_min_length') ,
				'max'		=>	self::config('name_max_length')
			)));
		}
		
		// Alphanumeric
		$lang = include dirname(__file__).DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.self::config('lang').'.php';
		
		if(!preg_match('/^[\w'.$lang['alphanumeric'].']+$/', $name))
		{
			die(self::line('error', array
			(
				'message'	=>	'nickname_alphanumeric'
			)));
		}
		
		// Case sensitivity
		$case_sensitive = self::config('name_case_sensitive');
		
		$cmp = $case_sensitive?$name:mb_strtolower($name);
		
		// Password
		$users = self::config('users');
		
		$names = array();
		
		foreach($users as $key=>$value)
		{
			$names[] = $case_sensitive?$key:mb_strtolower($key);
		}
		
		if(in_array($cmp, $names))
		{
			if($password === null)
			{
				die(self::line('error', array
				(
					'message'	=>	'nickname_no_pass'
				)));
			}
			
			if($password != $users[$cmp][0])
			{
				die(self::line('error', array
				(
					'message'	=>	'nickname_wrong_pass'
				)));
			}
			
			$write['@level'] = $users[$cmp][1];
		}
		
		// Online
		$file = fopen(self::path('online'), 'r');
		
		while($raw = fgets($file))
		{
			$line = explode(self::DELIMITER, $raw);
			
			if($line[0] == self::$user['session'])
			{
				continue;
			}
			
			$user = self::user($line[0]);
			
			if($case_sensitive?$user['name']:mb_strtolower($user['name']) == $cmp)
			{
				fclose($file);
				
				die(self::line('error', array
				(
					'message'	=>	'nickname_in_use'
				)));
			}
		}
		
		fclose($file);
		
		// Write
		$previous = self::$user['name'];
		
		self::user('session', $write);
		
		echo self::write('nickname_change', array_merge(self::$user, array('previous_nickname'=>$previous)));
	}
	
	// Mute
	private static function command_mute($id, $time)
	{
		$user = self::user($id);
		
		// Self
		if($id == self::$user['session'])
		{
			exit;
		}
		
		// Higher level
		if(self::$user['level'] <= $user['level'])
		{
			exit;
		}
		
		// Time
		if($time != '0' && $time < self::ts())
		{
			exit;
		}
		
		// Write
		self::user($id, array
		(
			'@muted'	=>	$time
		));
		
		echo self::write('mute', array
		(
			'user'		=>	$id,
			'moderator'	=>	self::$user['session'],
			'time'		=>	$time
		));
	}
	
	// Unmute
	private static function command_unmute($id)
	{
		self::user($id, array
		(
			'@muted'	=>	null
		));
		
		echo self::write('unmute', array
		(
			'user'		=>	$id,
			'moderator'	=>	self::$user['session']
		));
	}
	
	// Ban
	private static function command_ban($id, $time)
	{
		$user = self::user($id);
		
		// Self
		if($id == self::$user['session'])
		{
			exit;
		}
		
		// Higher level
		if(self::$user['level'] <= $user['level'])
		{
			exit;
		}
		
		// Time
		if($time != '0' && $time < self::ts())
		{
			exit;
		}
		
		// Write
		self::user($id, array
		(
			'ban'	=>	$time
		));
		
		echo self::write('ban', array
		(
			'user'		=>	$id,
			'moderator'	=>	self::$user['session'],
			'time'		=>	$time
		));
	}
	
	// Unmute
	private static function command_unban($id)
	{
		self::user($id, array
		(
			'ban'	=>	null
		));
		
		echo self::write('unban', array
		(
			'user'		=>	$id,
			'moderator'	=>	self::$user['session']
		));
	}
	
	// Set user variable
	private static function command_set($id, $key, $value)
	{
		self::user($id, array
		(
			$key => $value
		));
		
		echo self::write('set_user_var', array
		(
			'session'	=>	$id,
			'key'		=>	$key,
			'value'		=>	$value
		));
	}
	
	// Close
	private static function command_close()
	{
		self::close_channel();
	}
}