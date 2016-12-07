<?php
mb_internal_encoding('UTF-8');

require '../SquareLC.php';

// Initialize
SquareLC::init($_GET['channel']);

// Language
$lang = require '../lang/'.SquareLC::config('lang').'.php';

$error = false;
$case_sensitive = SquareLC::config('name_case_sensitive');

// Banned
if(isset(SquareLC::$user['ban']))
{
	$error = 'you_are_banned';
} else
{
	// Online
	$online = array();

	$users = SquareLC::online(true);

	if(isset(SquareLC::$user['name']))
	{
		$cmp = $case_sensitive?SquareLC::$user['name']:mb_strtolower(SquareLC::$user['name']);
	}

	foreach($users as $id)
	{
		if($id == SquareLC::$user['session'])
		{
			$online[$id] = SquareLC::$user;
		} else
		{
			$online[$id] = SquareLC::user($id);
			
			if(isset(SquareLC::$user['name']) && ($case_sensitive?$online[$id]['name']:mb_strtolower($online[$id]['name']) == $cmp))
			{
				unset(SquareLC::$user['name'], SquareLC::$user['level']);
			}
		}
	}

	// User
	$write = array();

	if(!isset(SquareLC::$user['name']))
	{
		$names = array();
		$case_sensitive = SquareLC::config('name_case_sensitive');
		
		foreach($online as $user)
		{
			if(isset($user['name']))
			{
				$names[] = $case_sensitive?$user['name']:strtolower($user['name']);
			}
		}
		
		$index = 0;
		$prefix = ($case_sensitive?$lang['default_nickname']:strtolower($lang['default_nickname'])).'_';
		
		while(++$index)
		{
			$number = str_pad($index, 4, '0', STR_PAD_LEFT);
			
			if(!in_array($prefix.$number, $names))
			{
				$write['@name'] = $lang['default_nickname'].'_'.$number;
				
				break;
			}
		}
	}

	if(!isset(SquareLC::$user['level']))
	{
		$write['@level'] = 0;
	}

	if($write)
	{
		SquareLC::user('session', $write);
	}
	
	// Max users
	if(SquareLC::config('max_users') && count($online) >= SquareLC::config('max_users') && !isset($online[SquareLC::$user['session']]))
	{
		$error = 'room_full';
	} else
	{
		// Join
		if(!isset($online[SquareLC::$user['session']]))
		{
			$file = fopen(SquareLC::path('online'), 'a');
			
			fwrite($file, SquareLC::$user['session'].SquareLC::DELIMITER.SquareLC::ts().PHP_EOL);
			
			fclose($file);
			
			SquareLC::write('join', SquareLC::$user);
			
			$online[SquareLC::$user['session']] = SquareLC::$user;
		}

		// Log
		$log = '';
		
		if(SquareLC::config('log'))
		{
			$filename = SquareLC::path('log', null, false);
			
			if(file_exists($filename))
			{
				$lines = array();
				
				$file = fopen($filename, 'r');
				
				while($line = fgets($file))
				{
					$lines[] = $line;
				}
				
				fclose($file);
				
				if(is_numeric(SquareLC::config('log')))
				{
					$lines = array_slice($lines, -SquareLC::config('log'));
				}
				
				$log = implode('', $lines);
			}
		}
	}
}
?><!DOCTYPE html>
<html>
	<head>
		<title>SquareLC</title>
		<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
		<link rel="stylesheet" href="../css/layout.css"/>
		<link rel="stylesheet" href="../theme/<?php echo SquareLC::config('style'); ?>.css"/>
		<?php if(!$error): ?>
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript">
		<!--
		
		<?php if(!isset(SquareLC::$user['id']) && SquareLC::config('tracking') == 'cookie'): ?>
		var cookie = "<?php echo $_COOKIE['SquareLC']; ?>";
		<?php else: ?>
		var cookie = false;
		<?php endif; ?>
		
		var PHP_EOL = <?php echo json_encode(PHP_EOL); ?>;
		var PHP_SEP = <?php echo json_encode(SquareLC::DELIMITER); ?>;
		
		var channel = "<?php echo SquareLC::$channel; ?>";
		var session = "<?php echo SquareLC::$user['session']; ?>";
		
		var last_ts = <?php echo SquareLC::ts(); ?>;
		var last_send = 0;
		
		var config =
		{
			'load_lines': <?php echo SquareLC::config('load_lines'); ?>,
			'load_online': <?php echo SquareLC::config('load_online'); ?>,
			
			'send_timeout': <?php echo SquareLC::config('send_timeout'); ?>,
			
			'commands': <?php echo json_encode(SquareLC::config('commands')); ?>,
			
			'name_min_length': <?php echo SquareLC::config('name_min_length'); ?>,
			'name_max_length': <?php echo SquareLC::config('name_max_length'); ?>,
			'name_case_sensitive': <?php echo SquareLC::config('name_case_sensitive')?'true':'false'; ?>,
			
			'profile_path': <?php echo json_encode(SquareLC::config('profile_path')); ?>,
			
			'emoticons': <?php echo json_encode(SquareLC::config('emoticons')); ?>
		};
		
		var lang = <?php echo json_encode($lang); ?>;
		
		var users = <?php echo json_encode($online); ?>;
		
		var log = <?php echo json_encode($log); ?>;
		
		-->
		</script>
		<script type="text/javascript" src="../js/SquareLC.js"></script>
		<?php endif; ?>
	</head>
	<body>
		<div id="container">
			<?php if($error): ?>
			<div id="lines">
				<p class="error"><?php echo $lang[$error]; ?></p>
			</div>
			<?php else: ?>
			<div id="top">
				<span id="title"></span>
				<span id="count"></span>
				<div class="clear"><!-- --></div>
			</div>
			<div id="lines">
				<p class="error"><?php echo $lang['noscript']; ?></p>
			</div>
			<div id="online"></div>
			<form action="" id="send">
				<input id="input" autocomplete="off" maxlength="<?php echo SquareLC::config('msg_max_length'); ?>"/><input id="button" type="submit" value="<?php echo $lang['send']; ?>"/>
			</form>
			<?php endif; ?>
		</div>
	</body>
</html>