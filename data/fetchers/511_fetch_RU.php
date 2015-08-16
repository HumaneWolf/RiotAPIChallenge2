<?php

require 'config.php';
require 'db/connect.php';

echo "Loading...\n";
//RU
$matches = file_get_contents("riot_dataset/5.11/RANKED_SOLO/RU.json");
$matches = json_decode($matches, TRUE);

foreach ($matches as $m) {
	rustart:
	echo "Loading RU " . $m . "\n";
	$json = @file_get_contents("https://ru.api.pvp.net/api/lol/ru/v2.2/match/" . $m . "?api_key=" . $api['key']);
	$json = json_decode($json, TRUE);
	if (getHTTP($http_response_header[0]) == 429) {
		echo "ERROR: 429 - Rate limit reached/exeeded. Waiting 10 seconds for limit reset.\n";
		sleep(10);
		goto rustart;
	} elseif (getHTTP($http_response_header[0]) == 403) {
		die("FATAL ERROR: 403 - blacklist?");
	} elseif (getHTTP($http_response_header[0]) == 401) {
		die("FATAL ERROR: 401 - Invalid or missing api key");
	} elseif (getHTTP($http_response_header[0]) == 404) {
		echo "ERROR: 404 - Match not found. Skipping...\n";
	} elseif (getHTTP($http_response_header[0]) == 503) {
		echo "ERROR: 503 - Service unavailable. This might be just a moment, retrying in 2 minutes.\n";
		sleep(120);
		goto rustart;
	} else {
		//MATCH ID
		//$m = match id

		//SERVER
		$server = $json['region'];

		foreach ($json['participants'] as $p) {
			//OTHER ITEMS RESET
			$other = 0;
			$otherap = 0;
			$appart = 0;

			//CHAMP PLAYED
			$champ = $p['championId'];

			//WINNER
			if (isset($p['stats']['winner']) && $p['stats']['winner'] == TRUE) {
				$win = TRUE;
			} else {
				$win = FALSE;
			}

			//DMG TO CHAMPS
			if (isset($p['stats']['totalDamageDealtToChampions'])) {
				$dmg = $dmg = $p['stats']['totalDamageDealtToChampions'];
			} else {
				$dmg = 0;
			}

			//ITEMS
			if (isset($p['stats']['item0'])) {
				$item = intval($p['stats']['item0']);
				if ($item == 1058 || $item == 3089 || $item == 3157 || $item == 3285 || $item == 3116 || $item == 3003 || $item == 3040 || $item == 3027 || $item == 3136 || $item == 3151 || $item == 3135 || $item == 3115 || $item == 3152 || $item == 3165 || $item == 3174 || $item == 1026) { //is a changed ap item
					if ($s = $sql->prepare("INSERT INTO items_511 (champ, item, win, matchid, server) VALUES (?, ?, ?, ?, ?)")) {
						$s->bind_param("ssiss", $champ, $item, intval($win), $m, $server);
						if ($s->execute()) {
							echo "Saved item " . $item . "\n";
						} else {
							$otherap = $otherap + 1;
							echo "failed to save an item 2 (!!!) \n";
						}
					} else {
						$otherap = $otherap + 1;
						echo "failed to save an item (!!!) \n";
					}
				} elseif ($item == 3001 || $item == 3124 || $item == 3146 || $item == 3025 || $item == 3092 || $item == 3078 || $item == 3023 || $item == 3504 || $item == 1056 || $item == 3060 || $item == 3100 || $item == 3041) { //is other ap item
					$otherap = $otherap + 1;
				} elseif ($item == 3145 || $item == 3057 || $item == 3113 || $item == 1052 || $item == 3098 || $item == 3303 || $item == 3191) { //is an ap part
					$appart = $appart + 1;
				} else { //is other item
					$other = $other + 1;
				}
			}

			if (isset($p['stats']['item1'])) {
				$item = intval($p['stats']['item1']);
				if ($item == 1058 || $item == 3089 || $item == 3157 || $item == 3285 || $item == 3116 || $item == 3003 || $item == 3040 || $item == 3027 || $item == 3136 || $item == 3151 || $item == 3135 || $item == 3115 || $item == 3152 || $item == 3165 || $item == 3174 || $item == 1026) { //is a changed ap item
					if ($s = $sql->prepare("INSERT INTO items_511 (champ, item, win, matchid, server) VALUES (?, ?, ?, ?, ?)")) {
						$s->bind_param("ssiss", $champ, $item, intval($win), $m, $server);
						if ($s->execute()) {
							echo "Saved item " . $item . "\n";
						} else {
							$otherap = $otherap + 1;
							echo "failed to save an item 2 (!!!) \n";
						}
					} else {
						$otherap = $otherap + 1;
						echo "failed to save an item (!!!) \n";
					}
				} elseif ($item == 3001 || $item == 3124 || $item == 3146 || $item == 3025 || $item == 3092 || $item == 3078 || $item == 3023 || $item == 3504 || $item == 1056 || $item == 3060 || $item == 3100 || $item == 3041) { //is other ap item
					$otherap = $otherap + 1;
				} elseif ($item == 3145 || $item == 3057 || $item == 3113 || $item == 1052 || $item == 3098 || $item == 3303 || $item == 3191) { //is an ap part
					$appart = $appart + 1;
				} else { //is other item
					$other = $other + 1;
				}
			}

			if (isset($p['stats']['item2'])) {
				$item = intval($p['stats']['item2']);
				if ($item == 1058 || $item == 3089 || $item == 3157 || $item == 3285 || $item == 3116 || $item == 3003 || $item == 3040 || $item == 3027 || $item == 3136 || $item == 3151 || $item == 3135 || $item == 3115 || $item == 3152 || $item == 3165 || $item == 3174 || $item == 1026) { //is a changed ap item
					if ($s = $sql->prepare("INSERT INTO items_511 (champ, item, win, matchid, server) VALUES (?, ?, ?, ?, ?)")) {
						$s->bind_param("ssiss", $champ, $item, intval($win), $m, $server);
						if ($s->execute()) {
							echo "Saved item " . $item . "\n";
						} else {
							$otherap = $otherap + 1;
							echo "failed to save an item 2 (!!!) \n";
						}
					} else {
						$otherap = $otherap + 1;
						echo "failed to save an item (!!!) \n";
					}
				} elseif ($item == 3001 || $item == 3124 || $item == 3146 || $item == 3025 || $item == 3092 || $item == 3078 || $item == 3023 || $item == 3504 || $item == 1056 || $item == 3060 || $item == 3100 || $item == 3041) { //is other ap item
					$otherap = $otherap + 1;
				} elseif ($item == 3145 || $item == 3057 || $item == 3113 || $item == 1052 || $item == 3098 || $item == 3303 || $item == 3191) { //is an ap part
					$appart = $appart + 1;
				} else { //is other item
					$other = $other + 1;
				}
			}

			if (isset($p['stats']['item3'])) {
				$item = intval($p['stats']['item3']);
				if ($item == 1058 || $item == 3089 || $item == 3157 || $item == 3285 || $item == 3116 || $item == 3003 || $item == 3040 || $item == 3027 || $item == 3136 || $item == 3151 || $item == 3135 || $item == 3115 || $item == 3152 || $item == 3165 || $item == 3174 || $item == 1026) { //is a changed ap item
					if ($s = $sql->prepare("INSERT INTO items_511 (champ, item, win, matchid, server) VALUES (?, ?, ?, ?, ?)")) {
						$s->bind_param("ssiss", $champ, $item, intval($win), $m, $server);
						if ($s->execute()) {
							echo "Saved item " . $item . "\n";
						} else {
							$otherap = $otherap + 1;
							echo "failed to save an item 2 (!!!) \n";
						}
					} else {
						$otherap = $otherap + 1;
						echo "failed to save an item (!!!) \n";
					}
				} elseif ($item == 3001 || $item == 3124 || $item == 3146 || $item == 3025 || $item == 3092 || $item == 3078 || $item == 3023 || $item == 3504 || $item == 1056 || $item == 3060 || $item == 3100 || $item == 3041) { //is other ap item
					$otherap = $otherap + 1;
				} elseif ($item == 3145 || $item == 3057 || $item == 3113 || $item == 1052 || $item == 3098 || $item == 3303 || $item == 3191) { //is an ap part
					$appart = $appart + 1;
				} else { //is other item
					$other = $other + 1;
				}
			}

			if (isset($p['stats']['item4'])) {
				$item = intval($p['stats']['item4']);
				if ($item == 1058 || $item == 3089 || $item == 3157 || $item == 3285 || $item == 3116 || $item == 3003 || $item == 3040 || $item == 3027 || $item == 3136 || $item == 3151 || $item == 3135 || $item == 3115 || $item == 3152 || $item == 3165 || $item == 3174 || $item == 1026) { //is a changed ap item
					if ($s = $sql->prepare("INSERT INTO items_511 (champ, item, win, matchid, server) VALUES (?, ?, ?, ?, ?)")) {
						$s->bind_param("ssiss", $champ, $item, intval($win), $m, $server);
						if ($s->execute()) {
							echo "Saved item " . $item . "\n";
						} else {
							$otherap = $otherap + 1;
							echo "failed to save an item 2 (!!!) \n";
						}
					} else {
						$otherap = $otherap + 1;
						echo "failed to save an item (!!!) \n";
					}
				} elseif ($item == 3001 || $item == 3124 || $item == 3146 || $item == 3025 || $item == 3092 || $item == 3078 || $item == 3023 || $item == 3504 || $item == 1056 || $item == 3060 || $item == 3100 || $item == 3041) { //is other ap item
					$otherap = $otherap + 1;
				} elseif ($item == 3145 || $item == 3057 || $item == 3113 || $item == 1052 || $item == 3098 || $item == 3303 || $item == 3191) { //is an ap part
					$appart = $appart + 1;
				} else { //is other item
					$other = $other + 1;
				}
			}

			if (isset($p['stats']['item5'])) {
				$item = intval($p['stats']['item5']);
				if ($item == 1058 || $item == 3089 || $item == 3157 || $item == 3285 || $item == 3116 || $item == 3003 || $item == 3040 || $item == 3027 || $item == 3136 || $item == 3151 || $item == 3135 || $item == 3115 || $item == 3152 || $item == 3165 || $item == 3174 || $item == 1026) { //is a changed ap item
					if ($s = $sql->prepare("INSERT INTO items_511 (champ, item, win, matchid, server) VALUES (?, ?, ?, ?, ?)")) {
						$s->bind_param("ssiss", $champ, $item, intval($win), $m, $server);
						if ($s->execute()) {
							echo "Saved item " . $item . "\n";
						} else {
							$otherap = $otherap + 1;
							echo "failed to save an item 2 (!!!) \n";
						}
					} else {
						$otherap = $otherap + 1;
						echo "failed to save an item (!!!) \n";
					}
				} elseif ($item == 3001 || $item == 3124 || $item == 3146 || $item == 3025 || $item == 3092 || $item == 3078 || $item == 3023 || $item == 3504 || $item == 1056 || $item == 3060 || $item == 3100 || $item == 3041) { //is other ap item
					$otherap = $otherap + 1;
				} elseif ($item == 3145 || $item == 3057 || $item == 3113 || $item == 1052 || $item == 3098 || $item == 3303 || $item == 3191) { //is an ap part
					$appart = $appart + 1;
				} else { //is other item
					$other = $other + 1;
				}
			}

			if ($s = $sql->prepare("INSERT INTO games_511 (matchid, champ, win, server, other, dmg, otherap, appart) VALUES (?, ?, ?, ?, ?, ?, ?, ?)")) {
				$s->bind_param("ssisisii", $m, $champ, intval($win), $server, $other, $dmg, $otherap, $appart);
				if ($s->execute()) {
					echo "done\n";
				} else {
					echo "failed to save game (!!!)\n";
				}
			} else {
				echo "failed to save game 2 (!!!)\n";
			}
		}
	}
}

require 'db/disconnect.php';