<?php


try {
    /*** connect to SQLite database ***/
    $dbh = new PDO("sqlite:./db/ft.db");
    
        /*** The SQL SELECT statement ***/
    $sql = "select max(id)+1 from newsletter;";
    foreach ($dbh->query($sql) as $row)
        {
        print $row[0] . '<br />';
        }

    /*** close the database connection ***/
    $dbh = null;
    
    
    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }


    
    

?>