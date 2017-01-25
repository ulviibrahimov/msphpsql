--TEST--
Data type precedence: conversion VARCHAR(n)
--SKIPIF--
--FILE--
<?php

require_once("autonomous_setup.php");

// Connect
// $connectionInfo = array("UID"=>$username, "PWD"=>$password, "CharacterSet"=>"UTF-8");
$conn = sqlsrv_connect($serverName, $connectionInfo) ?: die();

// Create database
sqlsrv_query($conn,"CREATE DATABASE ". $dbName) ?: die();

// Create table. Column names: passport
$sql = "CREATE TABLE $tableName (c1 VARCHAR(30))";
$stmt = sqlsrv_query($conn, $sql);

// Insert data. The data type with the lower precedence is 
// converted to the data type with the higher precedence
$sql = "INSERT INTO $tableName VALUES (''),(-378.4),(43000.4),(GETDATE())";
$stmt = sqlsrv_query($conn, $sql);

// Insert more data
// $sql = "INSERT INTO $tableName VALUES (''),('Galaxy'),('-- GO'),(N'银河系')";
// $stmt = sqlsrv_query($conn, $sql);

$err =  sqlsrv_errors();

print_r($err[0]);
/*
// Prepare the statement
$sql = "SELECT * FROM $tableName";
$stmt = sqlsrv_prepare( $conn, $sql);

// Get and display field metadata
foreach(sqlsrv_field_metadata($stmt) as $meta)
{
	print_r($meta);
}
*/
// echo "OK";

// Read data from the table
$sql = "SELECT * FROM $tableName";
$stmt = sqlsrv_query($conn, $sql);
// if( $stmt === false ) { die( print_r( sqlsrv_errors(), true )); }

// echo "OK";

// var_damp($stmt);

for($i=0; $i<3; $i++)
{
	$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_NUMERIC);
	var_dump($row[0]);
}

// DROP database
sqlsrv_query($conn,"DROP DATABASE ". $dbName);

// Free statement and connection resources
sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

print "Done"
?>

--EXPECT--
string(19) "Jan  1 1900 12:00AM"
string(19) "Dec 18 1898  2:24PM"
string(19) "Sep 24 2017  9:36AM"
Done
