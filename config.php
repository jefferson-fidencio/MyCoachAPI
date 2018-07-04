<?php
header('Content-Type: text/html; charset=utf-8');

$databaseHost = 'localhost';
$databaseNome = 'hshpe859_hshDB';
$databaseUsernome = 'hshpe859_jeff';
$databasePassword = 'admin12345';

$mysqli = mysqli_connect($databaseHost, $databaseUsernome, $databasePassword, $databaseNome);

?>
