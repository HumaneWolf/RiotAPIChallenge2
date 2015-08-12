<?php

$mysql['server'] = "localhost"; //Database server address/ip
$mysql['database'] = ""; //Database name
$mysql['user'] = ""; //Database username
$mysql['password'] = ""; //Database user password

$api['key'] = "";


function getHTTP($in) {
	preg_match("#HTTP/[0-9\.]+\s+([0-9]+)#", $in, $out);
	return $out['1'];
}