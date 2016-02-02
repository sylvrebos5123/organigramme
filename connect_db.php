<?php
/*******/

$rootpath = addslashes($_SERVER["DOCUMENT_ROOT"]);


$host='cpasxldb';
 $user='root';
 $password='root';
 $database='cpas_test';

$lien=mysqli_connect($host,$user,$password,$database); 
mysqli_set_charset($lien, 'utf8');
?>