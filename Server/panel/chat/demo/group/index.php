<?php
// Include the main SquareLC file
require '../../SquareLC/SquareLC.php';

// Initialize SquareLC and select the global channel
SquareLC::init('global');



SquareLC::user('session', array
(
	'id'		=>	1,
	
	'@name'		=>	'sercan',
	'@level'	=>	3,
	'@lock'		=>	true
));

?><!DOCTYPE html>
<html>
	<head>
		<title>Square Live Chat demo</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	</head>
	<body>
		<?php
		// Output the chat
		SquareLC::chat(array
		(
			'width'		=>	650,
			'height'	=>	400
		));
		?>
	</body>
</html>