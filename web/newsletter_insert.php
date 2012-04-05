<?php

try {
    /*** connect to SQLite database ***/
    $dbh = new PDO("sqlite:db/ft.db");

	/*** This is inefficient, but I could not get last_insert_rowid()+1 to work ***/
    $sql = "select max(id)+1 from newsletter;";
    foreach ($dbh->query($sql) as $row)
    {
        $nextId = $row[0];
    }            
    /*** The SQL insert statement ***/	    
	if($_REQUEST["email"] != '' && $dbh->exec("INSERT INTO newsletter(id, email) VALUES (".$nextId.", '" . $_REQUEST["email"] . "')")) {
    	echo 'true';
    } else {
    	echo 'false';
    	error_log("COULD NOT INSERT into sqlite: " . "INSERT INTO newsletter(id, email) VALUES (".$nextId.", '" . $_REQUEST["email"] . "')");
    }    
	
    /*** close the database connection ***/
    $dbh = null;

    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }



?>
