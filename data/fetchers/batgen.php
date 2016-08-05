<?php
//Generate the batch files to run each PHP files, for simplicity. Edit version in put contents.

$servers = array("BR", "EUNE", "EUW", "KR", "LAN", "LAS", "NA", "OCE", "TR", "RU");

foreach ($servers as $s) {
	file_put_contents("RUN_FETCH_" . $s . ".bat",
'D:\xampp\php\php.exe -f "514_fetch_' . $s . '.php"
pause');
}
