<?php


try {
    /*** connect to SQLite database ***/
    $dbh = new PDO("sqlite:./db/ft.db");
    
        /*** The SQL SELECT statement ***/
    $sql = "SELECT * FROM newsletter where email = 'arobson@pobox.com'";
    foreach ($dbh->query($sql) as $row)
        {
        print $row['Id'] .' - '. $row['Email'] . '<br />';
        }

    /*** close the database connection ***/
    $dbh = null;
    
    
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }


    
    

?>