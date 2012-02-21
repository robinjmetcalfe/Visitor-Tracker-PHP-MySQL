<?php
//change these values to whatever your own database server requires
$link = mysql_connect('localhost', 'root', 'pass'); //Connects to the database at "localhost"
if(!$link) {
    //halt execution if cannot connect
    die("Cannot connect to the database!");
}
mysql_select_db('test', $link); //Assuming you have a database named "test" set up
?>