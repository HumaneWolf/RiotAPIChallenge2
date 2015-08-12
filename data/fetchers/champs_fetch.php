<?php

require 'config.php';
require 'db/connect.php';

$champs = file_get_contents('https://global.api.pvp.net/api/lol/static-data/euw/v1.2/champion?locale=en_US&champData=all&api_key=' . $api['key']);

$champs = json_decode($champs, TRUE);

echo "Working...\n";

foreach ($champs['data'] as $c) {
	$name = $c['name'];
	$id = $c['id'];
	$title = $c['title'];

	if ($s = $sql->prepare("INSERT INTO champions (id, name, title) VALUES (?, ?, ?)")) {
		$s->bind_param("iss", $id, $name, $title);
		if ($s->execute()) {
			echo "Added " . $name . "\n";
		} else {
			echo "oops1\n";
		}
	} else {
		echo "oops2\n";
	}
}
echo "Done.\n";


require 'db/disconnect.php';