<?php

/**
 * Code examples from the tutorial
 */

require_once dirname(__FILE__) . '/SugestioClient.php';
require_once dirname(__FILE__) . '/SugestioUser.php';
require_once dirname(__FILE__) . '/SugestioItem.php';
require_once dirname(__FILE__) . '/SugestioConsumption.php';

$client = new SugestioClient();


try {
	
	// Uncomment the feature that you want to try out

	//getRecommendations();
	//getSimilar();
	//getNeighbours();
	
	//addConsumption();
	//addItem();
	//addUser();
	
	//getAnalytics();
    
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

function getRecommendations() {
	
	global $client;
	
	// get personal recommendations for user with id '1'
	$recommendations = $client->getRecommendations(1);
	
	echo '<pre>';
	print_r($recommendations);
	echo '</pre>';
	
}

function getSimilar() {

	global $client;
	
	// get items that are similar to the item with id '1'
	$recommendations = $client->getSimilar(1);
	
	echo '<pre>';
	print_r($recommendations);
	echo '</pre>';
	
}

function getNeighbours() {

	global $client;
	
	// get users that are similar to the user with id '1'
	$neighbours = $client->getNeighbours(1);
	
	echo '<pre>';
	print_r($neighbours);
	echo '</pre>';
	
}

function addConsumption() {
	
	global $client;
	
	$consumption = new SugestioConsumption(1, 'A'); // userid, itemid
	$consumption->type = 'RATING';
	$consumption->detail = 'STAR:5:1:3';
	$consumption->date = 'NOW'; 
	$result = $client->addConsumption($consumption);

	echo "addConsumption response code: $result";
	
}

function addItem() {
	
	global $client;
	
	$item = new SugestioItem('A');
	$item->tag = array('tag1', 'tag2');
	$item->category = array('category1', 'category2');
	$item->creator = array('artist1');	
	$item->location_latlong = '40.446195,-79.948862';	
	$result = $client->addItem($item);
	
	echo "addItem response code: $result";
}

function addUser() {
	
	global $client;
	
	$user = new SugestioUser(1);
	$user->gender = 'M';
	$user->birthday = '1974-03-20';
	$result = $client->addUser($user);
	
	echo "addUser response code: $result";
}

function getAnalytics() {
	
	global $client;
	
	$analytics = $client->getAnalytics(2);
	
	echo '<pre>';
	print_r($analytics);
	echo '</pre>';
}

?>