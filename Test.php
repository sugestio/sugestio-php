<?php

/**
 * This file contains sample code that illustrates how the SugestioClient object can
 * be used in a project. Visit the website for tutorials and general API documentation.
 */

require_once dirname(__FILE__) . '/SugestioClient.php';
require_once dirname(__FILE__) . '/SugestioUser.php';
require_once dirname(__FILE__) . '/SugestioItem.php';
require_once dirname(__FILE__) . '/SugestioConsumption.php';

require_once dirname(__FILE__) . '/Controller.php';

// test the sugestio client object
$test = new SugestioClientTest();

// test the controller object that provides backwards compatibility with the old library
//$test = new ControllerTest();

try {

    //$test->testAddUser();
    //$test->testAddItem();
    //$test->testAddConsumption();
    //$test->testGetRecommendations();
    //$test->testGetSimilar();
    //$test->testDeleteRecommendation();
    //$test->testGetAnalytics();
    
} catch (Exception $e) {
    echo $e->getMessage();
} 

/**
 * This class will test the SugestioClient.php functions
 */
class SugestioClientTest {

    private $client;

    public function __construct() {
        $this->client = new SugestioClient();
    }


    public function testAddUser() {

        $user = new SugestioUser(123);

        $user->gender = 'M';
        $user->birthday = '1975-04-09';
        $user->location_simple = 'US';
        $user->location_latlong = '40.446195,-79.948862';
        $user->apml = "http://...";
        $user->foaf = "http://...";

        $result = $this->client->addUser($user);

        echo "addUser response code: $result";

    }

    public function testAddItem() {

        $item = new SugestioItem('1234-AAA-5678');

        $item->description_short = 'short description';
        $item->description_full = 'full description';
        $item->permalink = 'http://...';

        $item->available = 'N';
        $item->from = '2010-01-01T00:00:00';
        $item->until = '2010-12-31T00:00:00';

        $item->location_simple = 'NY';
        $item->location_latlong = '40.446195,-79.948862';

        $item->category[] = 'Pop'; // category, creator, segment and tag are arrays
        $item->category[] = 'Rock';
        $item->creator[] = 'John Smith';
        $item->creator[] = 'James Smith';
        $item->segment = array('en-US', 'en-US');
        $item->tag = array('tag1', 'tag2');

        $result = $this->client->addItem($item);

        echo "addItem response code: $result";
    }

    public function testAddConsumption() {

        $c = new SugestioConsumption('1', 'abcd'); // userid, itemid

        $c->date = 'NOW'; // automatically assign the current date
        $c->type = 'RATING';
        $c->detail = 'THUMB:UP';
        $c->location_simple = 'home';
        $c->location_latlong = '40.446195,-79.948862';

        $result = $this->client->addConsumption($c);

        echo "addConsumption response code: $result";
    }

    public function testGetRecommendations() {

        //$recommendations = $this->client->getRecommendations(1, array('category' => 'music'));
        //$recommendations = $this->client->getRecommendations(1, array('segment' => 'en-US'));
        $recommendations = $this->client->getRecommendations(1);

        echo '<pre>';
        print_r($recommendations);
        echo '</pre>';
    }

    public function testGetSimilar() {

        $similar = $this->client->getSimilar(11256);

        echo '<pre>';
        print_r($similar);
        echo '</pre>';
    }

    public function testDeleteRecommendation() {

        $userid = 1;
        $itemid = 'abcd';

        $result = $this->client->deleteRecommendation($userid, $itemid);

        echo "deleteRecommendation response code: $result";
    }

    public function testGetAnalytics() {

        $result = $this->client->getAnalytics(5); // 5 most recent log entries

        echo '<pre>';
        print_r($result);
        echo '</pre>';
    }

}

/**
 * This class will test the Controller.php wrapper functions.
 */
class ControllerTest {

    private $controller;

    public function  __construct() {
        $this->controller = new Controller();
    }

    public function testAddUser() {
        $result = $this->controller->addUser(1, 'NY', '40.446195,-79.948862', 'M', '1975-04-09');
        echo "addUser response code: $result";
    }

    public function testAddItem() {

        $result = $this->controller->addItem(1, '2010-01-01T00:00:00', '2010-12-31T00:00:00',
                'vooruit', '40.446195,-79.948862',
                array('artist 1', 'artist 2'), array('tag 1', 'tag 2'), array('category 1', 'category 2'));

        echo "addItem response code: $result";
    }

    public function testAddConsumption() {
        
        $extra = array('phrase' => 'recommended for you', 'algorithm' => 'UserBasedCF');
        $result = $this->controller->addConsumptionExtra('1', 'abcdef', 'RATING', 'THUMB:UP', 'NOW', 'home', '40.446195,-79.948862', $extra);
        echo "addConsumption response code: $result";
    }

    public function testGetRecommendations() {

        //$recommendations = $this->client->getRecommendations(1, array('category' => 'music'));
        //$recommendations = $this->client->getRecommendations(1, array('segment' => 'en-US'));
        $recommendations = $this->controller->getRecommendations(1);

        echo '<pre>';
        print_r($recommendations);
        echo '</pre>';
    }

    public function testGetSimilar() {

        $similar = $this->controller->getSimilar(11256);

        echo '<pre>';
        print_r($similar);
        echo '</pre>';
    }

    public function testDeleteRecommendation() {
        $result = $this->controller->deleteRecommendation(1, 'abcd');
        echo "deleteRecommendation response code: $result";
    }

}
?>