<?php

$host='localhost';
$username='root';
$password='';

mysql_connect($host,$username,$password) or die('Couldnot Connect to the Server');

/*--- Creates a function to connect to different databases ---*/


function connect($database) {
	if(mysql_select_db($database)) {	
		return true;
	} else {
		return false;
	}
}
connect('nutriational_info') or die('Couldnot connect to the database');

?>
