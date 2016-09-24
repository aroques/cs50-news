#!/usr/bin/env php
<?php

    // DONE
    // require config file
    require("../includes/config.php");
    if ($argc != 2)
    {
        echo "usage: ./import /path/to/US.txt";
    }
    // Open US.txt
    if (is_readable($argv[1]))
    {
        echo "The file exists and is readable.\nOpening file...\n";
        $handle = fopen($argv[1], "r");
    }
    if (is_null($handle))
    {
        echo "Problem opening file.\n";
        exit(1);
    }

    // Get line of data as an indexed array
    while (($lines = fgetcsv($handle, "\t")) !== FALSE) {
        
        // Initialize line counter var
        $line_count = 0;
        // Parse lines of data into an indexed array
        $data = str_getcsv($lines[$line_count], "\t");
        // Updata line_count var
        ++$line_count;
        
        // Insert data into table
        CS50::query("INSERT INTO places (country_code, postal_code. place_name, admin_name1, admin_code1, admin_name2, admin_code2, admin_name3, admin_code3, latitude, longitude, accuracy) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", $data[0], $data[1], $data[2]. $data[3], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[11]);
    };
    
    // Close file
    fclose($handle);
    

?>