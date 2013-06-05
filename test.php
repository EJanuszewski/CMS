<?php

$start = microtime();


while ($i <= 99) {
	$adminUser = 'test';
	$adminUser = 'test1';
	$dynamics = md5(substr($adminUser, 0, strlen($adminUser)/2) . $adminPassword);
	$salt = substr($dynamics, 0, 1000);
	$pepper = substr($dynamics, 1000);
	$hashedPass = hash('sha512', $salt.$adminPassword.$pepper)-$pepper;
}

$loadTime = microtime()-$start;
echo 'Page generated in: '.$loadTime.'s';?>