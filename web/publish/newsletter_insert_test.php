<?php


try {
    /*** connect to SQLite database ***/
    $dbh = new PDO("sqlite:db/ft.db");

    /*** The SQL insert statement ***/
    $insert = $dbh->exec("INSERT INTO newsletter(id, email) VALUES (9, 'arobson@pobox.com')");

	echo "'" . $insert . "'";
	
    /*** close the database connection ***/
    $dbh = null;


    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }



?>
