<?php
$pagecontent = "";
$javascript = "";

$items = @file_get_contents("data/itemlist.json");
$items = json_decode($items, TRUE);

if (isset($_GET['champ'])) { //CHAMP PROFILE
	if ($champ = @file_get_contents("data/champs/" . intval($_GET['champ']) . ".json")) {
		if ($champ = json_decode($champ, TRUE)) {
			$javascript = '
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
			<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>';

			$friendly = str_replace(".", "", $champ['name']);
			$friendly = str_replace("'", "", $friendly);
			$friendly = str_replace(" ", "", $friendly);

			//Couple of special cases. Riot plz change
			//For some reason these are the 6 champions in the entire game who has an image with different casing than their name, and the champ with a different image name completely.
			if ($friendly == "LeBlanc") {
				$friendly = "Leblanc";
			}

			if ($friendly == "KhaZix") {
				$friendly = "Khazix";
			}

			if ($friendly == "Fiddlesticks") {
				$friendly = "FiddleSticks";
			}

			if ($friendly == "ChoGath") {
				$friendly = "Chogath";
			}

			if ($friendly == "ChoGath") {
				$friendly = "Chogath";
			}

			if ($friendly == "VelKoz") {
				$friendly = "Velkoz";
			}

			if ($friendly == "Wukong") {
				$friendly = "MonkeyKing";
			}

			$pagecontent .= '
			<div class="champprofile">
				<div class="pic">
					<img src="http://ddragon.leagueoflegends.com/cdn/5.15.1/img/champion/' . $friendly . '.png">
				</div>
				<div class="pro">
					<h3>' . $champ['name'] . '</h3>
					<p>' . $champ['title'] . '</p>
				</div>
			</div>

			<h3>Champion Statistics</h3>
			<table>
				<thead>
					<tr>
						<th class="thinified"></th>
						<th>Patch 5.11</th>
						<th>Patch 5.14</th>
						<th>Change</th>
					</tr>
				</thead>
				<tbody>
			';
			//winrate
			$pagecontent .= '
			<tr>
				<th>Winrate</th>
				<td>' . round($champ['5.11']['stats']['winrate'], 1) . ' %</td>
				<td>' . round($champ['5.14']['stats']['winrate'], 1) . ' %</td>';

			$improved = round($champ['5.14']['stats']['winrate'] - $champ['5.11']['stats']['winrate'], 3);
			if ($improved == 0) {
				$improved = "No change";
			} elseif ($improved > 0) {
				$improved = '<span class="better">+' . $improved . ' %</span>';
			} elseif ($improved < 0) {
				$improved = '<span class="worse">' . $improved . ' %</span>';
			}
			$pagecontent .= '<td>' . $improved . '</td>
			</tr>';

			//dmg
			$pagecontent .= '
			<tr>
				<th>Avarage Damage Dealt to Champs</th>
				<td>' . round($champ['5.11']['stats']['dmgtochamps']) . '</td>
				<td>' . round($champ['5.14']['stats']['dmgtochamps']) . '</td>';
			$improved = round($champ['5.14']['stats']['dmgtochamps'] - $champ['5.11']['stats']['dmgtochamps']);
			if ($improved == 0) {
				$improved = "No change";
			} elseif ($improved > 0) {
				$improved = '<span class="better">+' . $improved . '</span>';
			} elseif ($improved < 0) {
				$improved = '<span class="worse">' . $improved . '</span>';
			}
			$pagecontent .= '<td>' . $improved . '</td>
			</tr>
				</tbody>
			</table>';

			$javascript .= '
			<script type="text/javascript">
				google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(drawChart);
				function drawChart() {

				var data = google.visualization.arrayToDataTable([
					["Item", "% of all items bought"],
					["Other Items", ' . round($champ['5.11']['items']['other'], 2) . '],
					["Other AP items", ' . round($champ['5.11']['items']['otherap'], 2) . '],
					["Other AP Parts", ' . round($champ['5.11']['items']['appart'], 2) . ']';

			foreach ($champ['5.11']['items']['changed'] as $k => $v) {
				$javascript .= '
				,["' . $items['changed'][$k] . '", ' . round($v['pop'], 2) . ']
				';
			}

			$javascript .= '
				]);

				var options = {
				  backgroundColor: "#ecf0f1",
				  legend: "right" 
				};

				var chart = new google.visualization.PieChart(document.getElementById("items511"));

				chart.draw(data, options);
				}
			</script>
		    ';

		    $javascript .= '
			<script type="text/javascript">
				google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(drawChart);
				function drawChart() {

				var data = google.visualization.arrayToDataTable([
					["Item", "% of all items bought"],
					["Other Items", ' . round($champ['5.14']['items']['other'], 2) . '],
					["Other AP items", ' . round($champ['5.14']['items']['otherap'], 2) . '],
					["Other AP Parts", ' . round($champ['5.14']['items']['appart'], 2) . ']';

			foreach ($champ['5.14']['items']['changed'] as $k => $v) {
				$javascript .= '
				,["' . $items['changed'][$k] . '", ' . round($v['pop'], 2) . ']
				';
			}

			$javascript .= '
				]);

				var options = {
				  backgroundColor: "#ecf0f1",
				  legend: "right",
				};

				var chart = new google.visualization.PieChart(document.getElementById("items514"));

				chart.draw(data, options);
				}
			</script>
		    ';

			$pagecontent .= '
			<h3>Items bought</h3>
			<table>
				<thead>
					<tr>
						<th>Patch 5.11</th>
						<th>Patch 5.14</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><div id="items511" class="pie"></div></td>
						<td><div id="items514" class="pie"></div></td>
					</tr>
				</tbody>
			</table>

			<h3>Item Statistics on ' . $champ['name'] . '</h3>
			<table id="itemsbought">
			<thead>
				<tr>
					<th>Item</th>
					<th>Winrate 5.11</th>
					<th>Winrate 5.14</th>
					<th>Winrate Change</th>
					<th>Popularity 5.11</th>
					<th>Popularity 5.14</th>
					<th>Popularity Change</th>
				</tr>
			</thead>
			<tbody>';

			foreach ($champ['5.11']['items']['changed'] as $k => $v) {
				if (!isset($v['winrate'])) {
					$v['winrate'] = 0;
				}

				if (!isset($champ['5.14']['items']['changed'][$k]['winrate'])) {
					$champ['5.14']['items']['changed'][$k]['winrate'] = 0;
				}

				$winrate = round($champ['5.14']['items']['changed'][$k]['winrate'] - $v['winrate'], 2);
				if ($winrate == 0) {
					$winrate = 'No change';
				} elseif ($winrate > 0) {
					$winrate = '<div class="better">+' . $winrate . ' %</div>';
				} elseif ($winrate < 0) {
					$winrate = '<div class="worse">' . $winrate . ' %</div>';
				}

				$popularity = round($champ['5.14']['items']['changed'][$k]['pop'] - $v['pop'], 2);
				if ($popularity == 0) {
					$popularity = 'No change';
				} elseif ($popularity > 0) {
					$popularity = '<div class="better">+' . $popularity . ' %</div>';
				} elseif ($popularity < 0) {
					$popularity = '<div class="worse">' . $popularity . ' %</div>';
				}

				$pagecontent .= '
				<tr>
					<td><div class="img"><img src="http://ddragon.leagueoflegends.com/cdn/5.15.1/img/item/' . $k . '.png"></div>
					' . $items['changed'][$k] . '</td>
					<td class="wr">' . round($v['winrate'], 2) . ' %</td>
					<td class="wr">' . round($champ['5.14']['items']['changed'][$k]['winrate'], 2) . ' %</td>
					<td class="wr">' . $winrate . '</td>
					<td class="pop">' . round($v['pop'], 2) . ' %</td>
					<td class="pop">' . round($champ['5.14']['items']['changed'][$k]['pop'], 2) . ' %</td>
					<td class="pop">' . $popularity . '</td>
				</tr>';
			}

			$pagecontent .= '</tbody></table>
			<script>
			$(document).ready(function() 
			    { 
			        $("#itemsbought").tablesorter(); 
			    } 
			);
			</script>

			<p>Popularity: x% of items bought in the analyzed games. Counts items owned at the end of the game.</p>
			<p>Other items: Any item not giving AP. This includes some parts of AP items, such as Giants Belt.</p>
			';
		} else {
			$pagecontent .= '<h3>Failed to load champion.</h3>';
		}
	} else {
		$pagecontent .= '<h3>Invalid champion</h3>';
	}
} elseif (isset($_GET['items'])) { //ITEM LIST
	if ($itemstats = @file_get_contents("data/items.json")) {
		if ($itemstats = json_decode($itemstats, TRUE)) {
			$javascript = '
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
			<script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>';

			$pagecontent .= '<h3>Item Statistics</h3>
			<table id="items">
			<thead>
				<tr>
					<th>Item</th>
					<th>Winrate 5.11</th>
					<th>Winrate 5.14</th>
					<th>Winrate Change</th>
					<th>Popularity 5.11</th>
					<th>Popularity 5.14</th>
					<th>Popularity Change</th>
				</tr>
			</thead>
			<tbody>';

			foreach ($itemstats['5.11'] as $k => $v) {
				if (!isset($v['winrate'])) {
					$v['winrate'] = 0;
				}

				if (!isset($itemstats['5.14'][$k]['winrate'])) {
					$itemstats['5.14'][$k]['winrate'] = 0;
				}

				$winrate = round($itemstats['5.14'][$k]['winrate'] - $v['winrate'], 2);
				if ($winrate == 0) {
					$winrate = 'No change';
				} elseif ($winrate > 0) {
					$winrate = '<div class="better">+' . $winrate . ' %</div>';
				} elseif ($winrate < 0) {
					$winrate = '<div class="worse">' . $winrate . ' %</div>';
				}

				$popularity = round($itemstats['5.14'][$k]['pop'] - $v['pop'], 2);
				if ($popularity == 0) {
					$popularity = 'No change';
				} elseif ($popularity > 0) {
					$popularity = '<div class="better">+' . $popularity . ' %</div>';
				} elseif ($popularity < 0) {
					$popularity = '<div class="worse">' . $popularity . ' %</div>';
				}

				$pagecontent .= '
				<tr>
					<td><div class="img"><img src="http://ddragon.leagueoflegends.com/cdn/5.15.1/img/item/' . $k . '.png"></div>
					' . $items['changed'][$k] . '</td>
					<td class="wr">' . round($v['winrate'], 2) . ' %</td>
					<td class="wr">' . round($itemstats['5.14'][$k]['winrate'], 2) . ' %</td>
					<td class="wr">' . $winrate . '</td>
					<td class="pop">' . round($v['pop'], 2) . ' %</td>
					<td class="pop">' . round($itemstats['5.14'][$k]['pop'], 2) . ' %</td>
					<td class="pop">' . $popularity . '</td>
				</tr>';
			}

			$pagecontent .= '</tbody></table>
			<script>
			$(document).ready(function() 
			    { 
			        $("#items").tablesorter(); 
			    } 
			);
			</script>

			<p>Popularity: x% of items bought in the analyzed games.</p>
			';
		} else {
			$pagecontent .= '<h3>Failed to load items.</h3>';
		}
	} else {
		$pagecontent .= '<h3>Failed to load items.</h3>';
	}
} elseif (isset($_GET['about'])) { //ABOUT PAGE
	$pagecontent = '
	<div class="twitter">
		<a class="twitter-timeline" data-dnt="true" href="https://twitter.com/HumaneWolf" data-widget-id="536167276769464322">Tweets av @HumaneWolf</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	</div>
	<h3>About this Website</h3>
	<p>The AP Item Changes website is dedicated to showing statistics before and after the AP item changes in League of Legends patch 5.13. We do this through individual item statistics, item statistics on
	different champions and champion statistics.</p>
	<p>We display stats such as, winrate, popularity, damage, and more.</p>

	<h3>How it is made</h3>
	<p>When I saw the post about the <a href="https://developer.riotgames.com/discussion/announcements/show/2lxEyIcE">Riot Games API challenge 2.0</a>, I started by creating scripts to gather the
	information from the API. I programmed them in PHP and ran them overnight in the commandline using a batch file and saved relevant data in a MySQL database. Afterwards, I programmed PHP scripts
	to save all of the information I needed to .json files, which are used to store the information displayed on the website.</p>
	<p>After I had all the data, I started with the design of the website itself, and you are now reading a part of the result.</p>

	<h3>Featured Champions</h3>
	<p>The featured champions are decided to represent different kinds of AP champions, with different strengths and weaknesses.</p>
	<p>Ahri is a pretty general midlaner that uses a lot of spells, both to burst and to deal DPS depending on build and playstyle.</p>
	<p>Cassiopeia is one of the most iconic spell-spammers in the game, as she will cast E whenever possible as long as she her enemies are poisoned. This gives her a high demand for mana items,
	and in addition she tend to stack high ammounts of AP.</p>
	<p>Kayle is different in that she is an AP autoattacker, rather than a traditional caster. Because of this she will often buy attackspeed items such as "Nashors Tooth", instead of just AP,
	cooldown reduction or mana.</p>
	<p>Rumble is the king of damage over time effects and is a champion with high base damages. This, combined with him often being played top causes him to often build magic penetration and
	items giving tank stats. He is also a manaless champion.</p>
	<p>Veigar is the symbol of a burst mage, with his ultimate and high AP scaling. The AP scaling and the indefinitely stacking AP using his Dark Matter (Q) also makes him a strong AP stacker.</p>

	<h3>Used Libraries</h3>
	<p>The following code libraries are used:</p>
	<ul>
		<li><a href="https://jquery.com/">jQuery</a></li>
		<li><a href="https://google-developers.appspot.com/chart/interactive/docs/gallery/piechart">Google Visualization Charts</a></li>
		<li><a href="http://tablesorter.com/docs/">Tablesorter 2.0</a></li>
	</ul>

	<h3>Other Information</h3>
	<p>Made with love by HumaneWolf.</p>
	<p>Approx. 100 000 games analyzed on patch 5.11, and approx. 100 000 games analyzed on patch 5.14. Games are from all Riot Servers (BR, EUNE, EUW, KR, LAN, LAS, NA, OCE, RU and TR), using the
	provided dataset and the official API.</p>
	<p>Background is made by Riot Games. It has been slightly altered to fit the website better.</p>
	';
} else { //FRONT PAGE, CHAMPION LIST
	$pagecontent .= '
	<h3>Featured champions</h3>
	<div class="champlist">
		<div class="champ">
			<a href="?champ=103">
				<img src="http://ddragon.leagueoflegends.com/cdn/5.15.1/img/champion/Ahri.png">
				Ahri
			</a>
		</div>
		<div class="champ">
			<a href="?champ=69">
				<img src="http://ddragon.leagueoflegends.com/cdn/5.15.1/img/champion/Cassiopeia.png">
				Cassiopeia
			</a>
		</div>
		<div class="champ">
			<a href="?champ=10">
				<img src="http://ddragon.leagueoflegends.com/cdn/5.15.1/img/champion/Kayle.png">
				Kayle
			</a>
		</div>
		<div class="champ">
			<a href="?champ=68">
				<img src="http://ddragon.leagueoflegends.com/cdn/5.15.1/img/champion/Rumble.png">
				Rumble
			</a>
		</div>
		<div class="champ">
			<a href="?champ=45">
				<img src="http://ddragon.leagueoflegends.com/cdn/5.15.1/img/champion/Veigar.png">
				Veigar
			</a>
		</div>
	</div>

	<h3>All champions</h3>';
	if ($champs = @file_get_contents("data/champlist.json")) {
		if ($champs = json_decode($champs, TRUE)) {
			$pagecontent .= '<div class="champlist">';
			foreach ($champs as $c) {
				$friendly = str_replace(".", "", $c['name']);
				$friendly = str_replace("'", "", $friendly);
				$friendly = str_replace(" ", "", $friendly);

				//Couple of special cases. Riot plz change
				//For some reason these are the 6 champions in the entire game who has an image with different casing than their name, and the champ with a different image name completely.
				if ($friendly == "LeBlanc") {
					$friendly = "Leblanc";
				}

				if ($friendly == "KhaZix") {
					$friendly = "Khazix";
				}

				if ($friendly == "Fiddlesticks") {
					$friendly = "FiddleSticks";
				}

				if ($friendly == "ChoGath") {
					$friendly = "Chogath";
				}

				if ($friendly == "ChoGath") {
					$friendly = "Chogath";
				}

				if ($friendly == "VelKoz") {
					$friendly = "Velkoz";
				}

				if ($friendly == "Wukong") {
					$friendly = "MonkeyKing";
				}



				$pagecontent .= '<div class="champ">
				<a href="?champ=' . $c['id'] . '">
					<img src="http://ddragon.leagueoflegends.com/cdn/5.15.1/img/champion/' . $friendly . '.png">
					' . $c['name'] . '
				</a>
				</div>
				';
			}
			$pagecontent .= '</div>';
		} else {
			$pagecontent .= '<p>Failed to load champion list.</p>';
		}
	} else {
		$pagecontent .= '<p>Failed to load champion list.</p>';
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>AP Item Changes</title>
	<?php echo $javascript; ?>
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	<div class="wrapper">
		<div class="sheader">
			<div class="logo">
				<img src="//humanewolf.com/assets/logo.png">
			</div>
			<div class="title">
				<h1>AP Item Changes</h1>
				<h2>Patch 5.13 changes - Statistics</h2>
			</div>
		</div>
		<div class="menu">
			<div class="button"><a href="?">Champions</a></div>
			<div class="button"><a href="?items">Items</a></div>
			<div class="button"><a href="?about">About</a></div>
		</div>
		<div class="content">
			<?php echo $pagecontent; ?>
		</div>
		<div class="footer">
			<p>AP Item Changes isn't endorsed by Riot Games and doesn't reflect the views or opinions of Riot Games or anyone officially involved in producing or managing League of Legends. League of Legends and Riot Games are trademarks or registered trademarks of Riot Games, Inc. League of Legends © Riot Games, Inc.</p>
		</div>
	</div>
</body>
</html>