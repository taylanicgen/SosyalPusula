<?php
mb_internal_encoding('UTF-8');

require '../SquareLC.php';

SquareLC::init($_POST['channel']);

$args = array();

$count = (int) $_POST['args'];

if($count < 0)
{
	exit;
}

for($i=0; $i<$count; $i++)
{
	if(!isset($_POST['arg'.$i]))
	{
		exit;
	}
	
	$args[] = str_replace(SquareLC::DELIMITER, '', $_POST['arg'.$i]);
}

if(!isset($_POST['command']))
{
	exit;
}

SquareLC::command($_POST['command'], $args);