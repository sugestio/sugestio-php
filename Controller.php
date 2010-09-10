<?php

require_once dirname(__FILE__) . '/SugestioClient.php';
require_once dirname(__FILE__) . '/SugestioConsumption.php';
require_once dirname(__FILE__) . '/SugestioUser.php';
require_once dirname(__FILE__) . '/SugestioItem.php';

/**
 * This is a wrapper class for the SugestioClient.
 * It provides backwards compatibility for applications that
 * use the old RaaS PHP library.
 *
 * New projects should use SugestioClient directly.
 * 
 * @deprecated
 */
class Controller {

    private $client;

    /**
     * Creates a new instance of the Controller wrapper.
     * @deprecated
     */
    public function __construct($base_url=null, $account=null, $secretkey=null, $security=null) {
        $this->client = new SugestioClient($account, $secretkey, $base_url);
    }


    /*
        Called when a user registers on the client website.

        Arguments:

        id: User ID on the client website (required)
        location_simple: country, ...
        location_latlong: GPS coordinates (latitude, longitude)
        gender: 'm' or 'f'
        birthday: expressed as a UTC timestamp

        Response:

        202 Accepted: Job was put on the queue and will be processed
        400 Bad Request: Required arguments are missing or malformed
        401 Unauthorized: Missing or incorrect account credentials        
        406 Not Acceptable
        500 Internal Server Error
    */
    public function addUser($id, $location_simple, $location_latlong, $gender, $birthday) {

        $user = new SugestioUser($id);

        $user->gender = $gender;
        $user->birthday = $birthday;
        $user->location_simple = $location_simple;
        $user->location_latlong = $location_latlong;

        $result = $this->client->addUser($user);

        return $result;
    }

    /*
        Called when an item is added to the client website.

        id: Item ID on the client website (required)
        from: UTC timestamp indicating from when this item may be recommended. Ex: 2004-09-16T17:55:43.54Z
        until: UTC timestamp indicating until when this item may be recommended. Ex: 2004-09-16T17:55:43.54Z
        location_simple: country, venue, ...
        location_latlong: GPS coordinates. (latitude, longitude)
        creator (array): Artist, manufacturer, uploader, ...
        tag (array)
        category (array)

        Response

        202 Accepted: Job was put on the queue and will be processed
        400 Bad Request: Required arguments are missing or malformed
        401 Unauthorized: Missing or incorrect account credentials
        406 Not Acceptable
        500 Internal Server Error
    */
    public function addItem($id, $from, $until, $location_simple, $location_latlong, $creator=array(), $tag=array(), $category=array()) {

        $item = new SugestioItem($id);

        $item->from = $from;
        $item->until = $until;
        $item->location_simple = $location_simple;
        $item->location_latlong = $location_latlong;
        $item->category = $category; // category, creator, segment and tag are arrays
        $item->creator = $creator;
        $item->tag = $tag;

        $result = $this->client->addItem($item);

        return $result;
    }

    /*
        Arguments

        userid: ID of the user that consumed the item (required)
        itemid: ID of the item that was consumed (required)
        type: The type of consumption (i.e. VIEW, BASKET, RATING)
        detail: More information about the consumption
        date: The moment of consumption expressed as a UTC timestamp
        location_simple: Location where the item is consumed (home, office, ...)
        location_latlong: GPS coordinates (latitude, longitude)

        Response

        202 Accepted: Job was put on the queue and will be processed
        400 Bad Request: Required arguments are missing or malformed
        401 Unauthorized: Missing or incorrect account credentials
        406 Not Acceptable
        500 Internal Server Error
    */
    public function addConsumption($userid, $itemid, $type, $detail, $date, $location_simple, $location_latlong, $extra=array()) {

        $c = new SugestioConsumption($userid, $itemid); // userid, itemid

        $c->date = $date;
        $c->type = $type;
        $c->detail = $detail;
        $c->location_simple = $location_simple;
        $c->location_latlong = $location_latlong;
        $c->extra = $extra;

        $result = $this->client->addConsumption($c);

        return $result;
    }

    /*
     * Extended addConsumption method which allows to pass additional parameters through the
     * $extra option. This field is expected to be an array
     */
    public function addConsumptionExtra($userid, $itemid, $type, $detail, $date, $location_simple, $location_latlong, $extra) {
            return $this->addConsumption($userid, $itemid, $type, $detail, $date, $location_simple, $location_latlong, $extra);
    }

    /*
        Returns recommendations for the given user. Recommendations consist of an Item ID and a score.
        Recommendations are sorted by descending score.

        Arguments

        userid: ID of the user who's recommendations must be fetched (required)
    */
    public function getRecommendations($userid) {

        try {
            $recommendations = $this->client->getRecommendations($userid);
        } catch (Exception $e) {
            $recommendations = array();
        }

        return $recommendations;
    }

    /*
         A (negative) consumption is created so that the item won't return the next time recommendations are calculated.

         Arguments

         userid: ID of the user that consumed the item (required)
         itemid: ID of the item that was consumed (required)

         Response

        202 Accepted: Job was put on the queue and will be processed
        400 Bad Request: Required arguments are missing or malformed
        401 Unauthorized: Missing or incorrect account credentials
        406 Not Acceptable
        500 Internal Server Error
    */
    public function deleteRecommendation($userid, $itemId) {
        return $this->client->deleteRecommendation($userid, $itemId);
    }

    /*
     Returns similar items for the given item. Recommendations consist of an Item ID and a score.
     Recommendations are sorted by descending score.

     Arguments

     userid: ID of the item whose similar items must be fetched (required)
    */
    public function getSimilar($itemid) {

        try {
            $similar = $this->client->getSimilar($itemid);
        } catch (Exception $e) {
            $similar = array();
        }

        return $similar;
    }

    public function getRecommendationsXml($userid) {
        return $this->getRecommendations($userid);
    }

    public function getRecommendationsJson($userid) {
    	
    	$recs = $this->getRecommendations($userid); 
    	
    	for ($i=0; $i<count($recs); $i++) {			
			$recs[$i]['itemid'] = array($recs[$i]['itemid']);
			$recs[$i]['score'] = array($recs[$i]['score']);
			$recs[$i]['algorithm'] = array($recs[$i]['algorithm']);
		}
    	
        return json_encode($recs);
    }

    public function getRecommendationsCsv($userid) {
        return $this->getRecommendations($userid);
    }

    public function getSimilarXml($itemid) {
        return $this->getSimilar($itemid);
    }

    public function getSimilarJson($itemid) {
        
    	$recs = $this->getSimilar($itemid);
    	
    	for ($i=0; $i<count($recs); $i++) {			
			$recs[$i]['itemid'] = array($recs[$i]['itemid']);
			$recs[$i]['score'] = array($recs[$i]['score']);
			$recs[$i]['algorithm'] = array($recs[$i]['algorithm']);
		}
    	
        return json_encode($recs);    	
    }

    public function getSimilarCsv($itemid) {
        return $this->getSimilar($itemid);
    }

}
?>