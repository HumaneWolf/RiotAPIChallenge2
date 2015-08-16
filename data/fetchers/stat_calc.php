<?php
require 'config.php';
require 'db/connect.php';



$champs = $sql->query("SELECT * FROM champions");

$items = json_decode('{  
        "1058": "Needlessly Large Rod",
        "3089": "Rabadon\'s Deathcap",
        "3157": "Zhonya\'s Hourglass",
        "3285": "Luden\'s Echo",
        "3116": "Rylai\'s Crystal Scepter",
        "3003": "Archangel\'s Staff",
        "3040": "Seraph\'s Embrace",
        "3027": "Rod of Ages",
        "3136": "Haunting Guise",
        "3151": "Liandry\'s Torment",
        "3135": "Void Staff",
        "3115": "Nashor\'s Tooth",
        "3152": "Will of the Ancients",
        "3165": "Morellonomicon",
        "3174": "Athene\'s Unholy Grail",
        "1026": "Blasting Wand"
		}', TRUE);


//--------------------------- CHAMP PROCESSING ---------------------------//


while ($c = $champs->fetch_assoc()) { //Loop through all champs in the database table.
	//Basic info
	$cinfo['id'] = $c['id'];
	$cinfo['name'] = $c['name'];
	$cinfo['title'] = $c['title'];

	$imgname = str_replace(" ", "", $c['name']);
	$imgname = str_replace("'", "", $imgname);

	$cinfo['image'] = 'http://ddragon.leagueoflegends.com/cdn/5.15.1/img/champion/' . $imgname . '.png';

	//
	// 5.11
	//

	//Statistics and calculations

	//Calculating how often non-changed items are bought.
	$otheritems = $sql->query("SELECT 
		SUM(other) AS other, 
		SUM(otherap) AS otherap, 
		SUM(appart) AS appart, 
		AVG(win) AS winrate, 
		AVG(dmg) as dmgtochamps
		FROM games_511 WHERE champ=" . $c['id']);

	$otheritems = $otheritems->fetch_assoc();

	$cinfo['5.11']['stats']['winrate'] = $otheritems['winrate'];
	$cinfo['5.11']['stats']['dmgtochamps'] = $otheritems['dmgtochamps'];

	$stats['other'] = $otheritems['other'];
	$stats['appart'] = $otheritems['appart'];
	$stats['otherap'] = $otheritems['otherap'];


	$stats['total'] = intval($stats['other']) + intval($stats['otherap']) + intval($stats['appart']);

	//get winrate and calculate total times each changed item has been bought on the champ
	foreach ($items as $k => $v) {
		$changed = $sql->query("SELECT COUNT(id) AS amount, AVG(win) AS winrate FROM items_511 WHERE item=" . $k . " AND champ=" . $c['id']);
		$changed = $changed->fetch_assoc();
		$stats['changed'][$k] = $changed['amount'];

		$stats['total'] = $stats['total'] + intval($changed['amount']);

		$cinfo['5.11']['items']['changed'][$k]['winrate'] = $changed['winrate'];
	}

	//determine the popularity of each changed ap item.
	foreach ($items as $k => $v) {
		$cinfo['5.11']['items']['changed'][$k]['pop'] = (floatval($stats['changed'][$k]) / $stats['total']) * 100;
	}


	$cinfo['5.11']['items']['other'] = (floatval($stats['other']) / $stats['total']) * 100; //percentage other items
	$cinfo['5.11']['items']['otherap'] = (floatval($stats['otherap']) / $stats['total']) * 100; //percentage other ap items
	$cinfo['5.11']['items']['appart'] = (floatval($stats['appart']) / $stats['total']) * 100; //percentage other ap parts


	//
	// 5.14
	//

	//Statistics and calculations

	//Calculating how often non-changed items are bought.
	$otheritems = $sql->query("SELECT 
		SUM(other) AS other, 
		SUM(otherap) AS otherap, 
		SUM(appart) AS appart, 
		AVG(win) AS winrate, 
		AVG(dmg) as dmgtochamps
		FROM games_514 WHERE champ=" . $c['id']);

	$otheritems = $otheritems->fetch_assoc();

	$cinfo['5.14']['stats']['winrate'] = $otheritems['winrate'] * 100;
	$cinfo['5.14']['stats']['dmgtochamps'] = $otheritems['dmgtochamps'];

	$stats['other'] = $otheritems['other'];
	$stats['appart'] = $otheritems['appart'];
	$stats['otherap'] = $otheritems['otherap'];


	$stats['total'] = intval($stats['other']) + intval($stats['otherap']) + intval($stats['appart']);

	//get winrate and calculate total times each changed item has been bought on the champ
	foreach ($items as $k => $v) {
		$changed = $sql->query("SELECT COUNT(id) AS amount, AVG(win) AS winrate FROM items_514 WHERE item=" . $k . " AND champ=" . $c['id']);
		$changed = $changed->fetch_assoc();
		$stats['changed'][$k] = $changed['amount'];

		$stats['total'] = $stats['total'] + intval($changed['amount']);

		$cinfo['5.14']['items']['changed'][$k]['winrate'] = $changed['winrate'] * 100;
	}

	//determine the popularity of each changed ap item.
	foreach ($items as $k => $v) {
		$cinfo['5.14']['items']['changed'][$k]['pop'] = (floatval($stats['changed'][$k]) / $stats['total']) * 100;
	}


	$cinfo['5.14']['items']['other'] = (floatval($stats['other']) / $stats['total']) * 100; //percentage other items
	$cinfo['5.14']['items']['otherap'] = (floatval($stats['otherap']) / $stats['total']) * 100; //percentage other ap items
	$cinfo['5.14']['items']['appart'] = (floatval($stats['appart']) / $stats['total']) * 100; //percentage other ap parts


	file_put_contents("champs/" . $c['id'] . ".json", json_encode($cinfo));

	echo $c['name'] . " done\n";
}


//--------------------------- ITEM PROCESSING ---------------------------//

//
// 5.11
//

// ITEM ICON ON DDRAGON: http://ddragon.leagueoflegends.com/cdn/5.15.1/img/item/1058.png

$stats['total'] = 0;

foreach ($items as $k => $v) {
	$changed = $sql->query("SELECT COUNT(id) AS amount, AVG(win) AS winrate FROM items_511 WHERE item=" . $k);
	$changed = $changed->fetch_assoc();
	$stats['total'] = $stats['total'] + $changed['amount'];
	$stats['changed'][$k] = $changed['amount'];

	$iinfo['5.11'][$k]['winrate'] = $changed['winrate'] * 100;
}

foreach ($items as $k => $v) {
	$iinfo['5.11'][$k]['pop'] = ($stats['changed'][$k] / $stats['total']) * 100;
}

file_put_contents("items/items.json", json_encode($iinfo));
echo "Items done\n";

require 'db/disconnect.php';