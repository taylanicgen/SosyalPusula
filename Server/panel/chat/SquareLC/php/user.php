<?php
require '../SquareLC.php';

SquareLC::init($_GET['channel']);

echo SquareLC::line(SquareLC::user($_GET['session']));