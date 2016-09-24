<?php
    
    /*
     *   This file searches our database for places matching
     *   a HTTP GET request and stores those places into an array.
     */

    require(__DIR__ . "/../includes/config.php");

    // DONE: search database for places matching $_GET["geo"]
    $geo = $_GET["geo"];
    // Replace commas with spaces
    $geo = str_replace(",", " ", $geo);
    $geo = trim($geo);
    // Count how many words were entered into the search
    $geo = explode(" ", $geo);
    $count = count($geo);
    // Error out if the user did not provide input
    if ($count < 1)
    {
    	print("Please enter a query string.");
    }
    elseif ($count === 1)
    {
    	$geo = $geo[0];
    	// Check to see if param is a zip code
    	if(is_numeric($geo))
    	{
    		$places = CS50::query("SELECT * FROM places WHERE postal_code LIKE ? LIMIT 10", $geo . "%");	
    	}
    	elseif(strlen($geo) == 2)
    	{
    		// Check state abbreviation, called admin_code1
    		$places = CS50::query("SELECT * FROM places WHERE admin_code1 LIKE ? LIMIT 10", strtoupper($geo) . "%");
    	}
    	else
    	{
    		// Check city, called place_name in places table
    		$places = CS50::query("SELECT * FROM places WHERE place_name LIKE ? LIMIT 10", $geo . "%");
    	}
    	
    	if(empty($places))
    	{
    		// Check state, called admin_name1
    		$places = CS50::query("SELECT * FROM places WHERE admin_name1 LIKE ? LIMIT 10", $geo . "%");
    	}
    }
    elseif($count > 1)
    {
    	// Reassemble places into one long string
    	$geo = implode(" ", $geo);
    	// Search across multiple columns
    	$places = CS50::query("SELECT * FROM places WHERE MATCH(postal_code, place_name, admin_name1, admin_code1) AGAINST (?) LIMIT 10", $geo);
    }

    // output places as JSON (pretty-printed for debugging convenience)
    header("Content-type: application/json");
    print(json_encode($places, JSON_PRETTY_PRINT));

?>