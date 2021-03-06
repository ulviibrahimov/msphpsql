--TEST--
direct execution of an invalid query
--SKIPIF--

--FILE--
<?php
    require_once("autonomous_setup.php");

    $conn = new PDO( "sqlsrv:Server=$serverName; Database = tempdb ", $username, $password);
    
    $conn->exec("IF OBJECT_ID('table1', 'U') IS NOT NULL DROP TABLE table1");
    
    // execute a query with typo (spelling error in CREATE)
    $conn->exec("CRETE TABLE table1(id INT NOT NULL PRIMARY KEY, val VARCHAR(10)) ");
    print_r( $conn->errorCode() );
    echo "\n";
    
    // execute a properly formatted query
    $conn->exec("CREATE TABLE table1(id INT NOT NULL PRIMARY KEY, val VARCHAR(10)) ");
    print_r( $conn->errorCode() );
    echo "\n";
    
    // drop table1 and free connections
    $conn->exec("DROP TABLE table1");
    $conn = NULL;
?>
--EXPECT--
42000
00000