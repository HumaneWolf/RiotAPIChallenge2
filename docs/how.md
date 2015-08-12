#How it is done

##General file explanations:

51x_fetch_xx.php: These files fetch stats from specific servers and specific versions, based on the soloqueue games given in the dataset. They then process the data and save relevant information to the MySQL database.

champ_fetch.php: This fetches a complete champion list from the API and saves it to the database.

champ_jsongen.php: This takes the champions from the database and generates a json list of them.

item_jsongen.php: This takes an array with the relevant items I made and generates a json file.

*.bat: The .bat files are used to run various PHP files in the command line, rather than the browser.

stat_calc.php: Uses the information from the database and saves champ and item specific stats to json files.

batgen.php: Used to mass-generate some of the .bat files.

##Databse usage:

The database is not used for the final products, but it is used to save data before the final processing. It stores all champions in all the games, the result of the game, damage dealt, changed items bought, how many other items bought etc.

##Final processing/"jsonification"

Last but not least, the info is once again processed and systematicly saved to json files that will be used to store the information on the website when it is finished.