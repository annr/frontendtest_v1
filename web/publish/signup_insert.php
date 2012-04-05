<?php

try {
    /*** connect to SQLite database ***/
    $dbh = new PDO("sqlite:db/ft.db");

	/*** This is inefficient, but I could not get last_insert_rowid()+1 to work ***/
    $sql = "select max(id)+1 from customer;";
    foreach ($dbh->query($sql) as $row)
    {
        $nextId = $row[0];
    }            
    $insert = "INSERT INTO customer(id, email, url) VALUES (".$nextId.", '" . $_REQUEST["email"] . "', '" . $_REQUEST["url"] ."')";
    //echo $insert;
    /*** The SQL insert statement ***/	    
	if($_REQUEST["email"] != '' && $dbh->exec($insert)) {
    	echo 'true';
    } else {
    	echo 'false';
    	error_log("COULD NOT INSERT into sqlite: " . $insert);
    }    
	
    /*** close the database connection ***/
    $dbh = null;

    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }



?>
