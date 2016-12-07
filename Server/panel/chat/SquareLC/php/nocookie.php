<?php
require '../SquareLC.php';

unlink(SquareLC::path('users', md5($_GET['cookie'])));