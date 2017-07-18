<?php 
	$MYSQL_LOGIN = "root";
	$MYSQL_PASSWORD = "foxlink";
	$MYSQL_HOST = "192.168.65.230";

	$mysqli = new mysqli($MYSQL_HOST,$MYSQL_LOGIN,$MYSQL_PASSWORD,"swipecard");
	$mysqli->query("SET NAMES 'utf8'");	 
	$mysqli->query('SET CHARACTER_SET_CLIENT=utf8');
	$mysqli->query('SET CHARACTER_SET_RESULTS=utf8'); 
?>