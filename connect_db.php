<?php
/*******/

$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);


$host='localhost';
 $user='root';
 $password='root';
 $database='my_db';

$lien=mysqli_connect($host,$user,$password,$database); 
mysqli_set_charset($lien, 'utf8');
?>
