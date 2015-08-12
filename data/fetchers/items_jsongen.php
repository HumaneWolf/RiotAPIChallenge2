<?php


$items['changed']['1058'] = "Needlessly Large Rod";
$items['changed']['3089'] = "Rabadon's Deathcap";
$items['changed']['3157'] = "Zhonya's Hourglass";
$items['changed']['3285'] = "Luden's Echo";
$items['changed']['3116'] = "Rylai's Crystal Scepter";
$items['changed']['3003'] = "Archangel's Staff";
$items['changed']['3040'] = "Seraph's Embrace";
$items['changed']['3027'] = "Rod of Ages";
$items['changed']['3136'] = "Haunting Guise";
$items['changed']['3151'] = "Liandry's Torment";
$items['changed']['3135'] = "Void Staff";
$items['changed']['3115'] = "Nashor's Tooth";
$items['changed']['3152'] = "Will of the Ancients";
$items['changed']['3165'] = "Morellonomicon";
$items['changed']['3174'] = "Athene's Unholy Grail";
$items['changed']['1026'] = "Blasting Wand";

$items['other'] = array(3001, 3124, 3146, 3025, 3092, 3078, 3023, 3504, 1056, 3060, 3100, 3041);

$items['parts'] = array(3145, 3057, 3113, 1052, 3098, 3303, 3191);

file_put_contents("items.json", json_encode($items));