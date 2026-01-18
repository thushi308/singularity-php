<?php
echo "Hello";
session_start();
session_unset();
session_destroy();

// Prevent caching
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
header("Pragma: no-cache");

header("Location: login.php");
exit();


