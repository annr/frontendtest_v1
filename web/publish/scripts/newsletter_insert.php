<?php

try {
    /*** connect to SQLite database ***/
    $dbh = new PDO("sqlite:./db/ft.db");

    /*** The SQL insert statement ***/	    
    echo "INSERT INTO newsletter(id, email) VALUES (last_insert_rowid()+1, '" . $_REQUEST["email"] . "')";
	if($_REQUEST["email"] != '' && $dbh->exec("INSERT INTO newsletter(id, email) VALUES (last_insert_rowid()+1, '" . $_REQUEST["email"] . "')")) {
    	echo 'true';
    } else {
    	echo 'false';
    }    
	
    /*** close the database connection ***/
    $dbh = null;

    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }



?>
