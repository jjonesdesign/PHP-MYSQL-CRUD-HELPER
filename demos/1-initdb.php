<?php
//This fill will create the tables we need to play around with this demo
echo "<b>=== Initializing Database Data Model ===</b><br /><br />";

//Open DB Connection
$NetworkConnection = new NetworkConnection();
$PDO = $NetworkConnection->getNewDbConnection();

//Create our table to demo ID with auto_increment
try{
    echo "Createding Table 'with_id' <br />";
    
    $sql = "CREATE TABLE with_id (
        id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
        entry_string VARCHAR(100) NOT NULL,
        entry_int TINYINT(5) NOT NULL,
        created_at DATETIME default CURRENT_TIMESTAMP NOT NULL,
        updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP NULL);";
    $results = $PDO->exec($sql);

    if ($results) {
        echo "Created Table 'with_id' <br />";
    }
    
} catch (Exception $ex) {
    echo $ex->getMessage() . '<br /><br />';
}

//Create our table to demo UID with unique id being generated
try{
    echo "Createding Table 'with_uid' <br />";
    
    $sql = "CREATE TABLE with_uid (
        uid VARCHAR(20) NOT NULL PRIMARY KEY,
        entry_string VARCHAR(100) NOT NULL,
        entry_int TINYINT(5) NOT NULL,
        created_at DATETIME default CURRENT_TIMESTAMP NOT NULL,
        updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP NULL);";
    $results = $PDO->exec($sql);

    if ($results) {
        echo "Created Table 'with_uid' <br />";
    }
    
} catch (Exception $ex) {
    echo $ex->getMessage() . '<br /><br />';
}

//Create our table to track used UID's and the table it was used in
try{
    echo "Createding Table 'uids' <br />";
    
    $sql = "CREATE TABLE uids (
        uid VARCHAR(20) NOT NULL PRIMARY KEY,
        table_name VARCHAR(100) NOT NULL);";
    $results = $PDO->exec($sql);

    if ($results) {
        echo "Created Table 'uids' <br />";
    }
    
} catch (Exception $ex) {
    echo $ex->getMessage() . '<br /><br />';
}


//Close DB connection
$NetworkConnection->closeConnection();
echo "<br /><b>Complete. <a href='/'> <<< Go Back</a><b><br />";