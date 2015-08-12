<?php

require 'config.php';
require 'db/connect.php';

$champs = $sql->query("SELECT * FROM champions ORDER BY name ASC");

while ($r = $champs->fetch_assoc()) {
	$champ[$r['name']]['id'] = $r['id'];
	$champ[$r['name']]['name'] = $r['name'];
	$champ[$r['name']]['title'] = $r['title'];
}

$json = json_encode($champ);

file_put_contents("champions.json", $json);

require 'db/disconnect.php';