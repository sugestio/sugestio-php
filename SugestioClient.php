<?php

/**
 * A class for communicating with the Sugestio recommendation service.
 *  
 * The MIT License
 *
 * Copyright (c) 2010 Sugestio
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

include_once dirname(__FILE__) . '/Settings.php';
include_once dirname(__FILE__) . '/oauth-php/library/OAuthStore.php';
include_once dirname(__FILE__) . '/oauth-php/library/OAuthRequester.php';


class SugestioClient {

    protected $settings;

    /**
     * Create a new instance of the SugestioClient. Optionally specify $account and $secretkey
     * to override the values in Settings.php
     *
     * @param account the account name (optional)
     * @param secretkey the secret key (optional)
     * @param base_url the base url of the webservice (optional)
     */
    public function __construct($account=null, $secretkey=null, $base_url=null) {
        
        $this->settings = new Settings();

        if ($account != null)
            $this->settings->account = $account;

        if ($secretkey != null)
            $this->settings->secretkey = $secretkey;

        if ($base_url != null)
            $this->settings->base_url = $base_url;
    }

    /**
     * Adds a user. Returns the server response.
     * 
     * @param SugestioUser $user the user
     * @return int HTTP status code
     */
    public function addUser($user) {

        $method = 'POST';
        $resource = '/users';

        $result = $this->execute($method, $resource, $user->getFields());

        return $result['code'];
    }

    /**
     * Adds an item. Returns the server response.
     *
     * @param SugestioItem $item the item
     * @return int HTTP status code
     */
    public function addItem($item) {

        $method = 'POST';
        $resource = '/items';

        $result = $this->execute($method, $resource, $item->getFields());

        return $result['code'];
    }

    /**
     * Adds a consumption. Returns the server response code.
     *
     * @param SugestioConsumption $consumption the consumption
     * @return int HTTP status code
     */
    public function addConsumption($consumption) {

        $method = 'POST';
        $resource = '/consumptions';
        
        $result = $this->execute($method, $resource, $consumption->getFields());

        return $result['code'];
    }

    /**
     * Gets personal recommendations for the given userid.
     *
     * The $options array can have one of the following name-value pairs:
     * category => categoryid
     * segment => segmentid
     * e.g., array('category' => 'music') or array('segment', 'en-US')
     *
     * @param string $userid the userid
     * @param array $options name=>value array with request options
     * @exception Exception when the request failed due to clientside or serverside problems
     * @return array (itemid=>string, score=>double, certainty=>double, algorithm=>string)
     */    
    public function getRecommendations($userid, $options=array()) {
    	
    	$method = 'GET';
        $resource = '/users/' . urlencode($userid) . '/recommendations.json';

        $result = $this->execute($method, $resource, $options);

        if ($result['code'] == 200)
            return json_decode($result['body'], true);
        else
            throw new Exception($this->createExceptionMessage($result));
    }

    /**
     * Gets items that are similar to the given itemid.
     *
     * The $options array can have one of the following name-value pairs:
     * category => categoryid
     * segment => segmentid
     * e.g., array('category' => 'music') or array('segment', 'en-US')
     *
     * @param string $itemid the itemid
     * @param array $options name=>value array with request options
     * @exception Exception when the request failed due to clientside or serverside problems
     * @return array (itemid=>string, score=>double, certainty=>double, algorithm=>string)
     */
    public function getSimilar($itemid, $options=array()) {

        $method = 'GET';
        $resource = '/items/' . urlencode($itemid) . '/similar.json';

        $result = $this->execute($method, $resource, $options);

        if ($result['code'] == 200)
            return json_decode($result['body'], true);
        else
            throw new Exception($this->createExceptionMessage($result));
    }
    
    /**
     * Gets the neighbours of the given user.
     * 
     * @param string $userid the userid     
     * @exception Exception when the request failed due to clientside or serverside problems
     * @return array (userid=>string, score=>double, certainty=>double, algorithm=>string)
     */
	public function getNeighbours($userid) {

        $method = 'GET';
        $resource = '/users/' . urlencode($userid) . '/neighbours.json';

        $result = $this->execute($method, $resource, array());

        if ($result['code'] == 200)
            return json_decode($result['body'], true);
        else
            throw new Exception($this->createExceptionMessage($result));
    }

    /**
     * Deletes the given consumption.     
     * @param $consumptionid the consumptionid
     * return int HTTP status code
     */
    public function deleteConsumption($consumptionid) {
    	
    	$method = 'DELETE';
    	$resource = '/consumptions/' . urlencode($consumptionid) . '.json';
    	
    	$result = $this->execute($method, $resource, array());
    	
    	return $result['code'];
    }
    
    /**
     * Deletes all the metadata of the given user. Returns the server response.     
     * @param $userid the userid
     * @return int HTTP status code
     */
    public function deleteUser($userid) {
    	
    	$method = 'DELETE';
    	$resource = '/users/' . urlencode($userid) . '.json';
    	
    	$result = $this->execute($method, $resource, array());
    	
    	return $result['code'];
    }
    
    /**
     * Deletes all the metadata of the given item. Returns the server response.
     * @param $itemid the itemid
     * @return int HTTP status code
     */
    public function deleteItem($itemid) {
    	
    	$method = 'DELETE';
    	$resource = '/items/' . urlencode($itemid) . '.json';
    	
    	$result = $this->execute($method, $resource, array());
    	
    	return $result['code'];
    }
    
    /**
     * Indicates the user did not like this recommendation. Returns the server response.
     *
     * @param string $userid
     * @param string $itemid
     * @return array int HTTP status code
     */
    public function deleteRecommendation($userid, $itemid) {

        $method = 'DELETE';
        $resource = '/users/' . urlencode($userid) . '/recommendations/' . urlencode($itemid);

        $result = $this->execute($method, $resource);

        return $result['code'];
    }

    /**
     * Gets analytics data for this account. Returns an array of name=>value pairs.
     *
     * @param int $limit get this many records
     * @exception Exception when the request failed due to clientside or serverside problems
     * @return array ( array(name=>string, value=>string) )
     */
    public function getAnalytics($limit=100) {

        $method = 'GET';
        $resource = '/analytics.csv';

        $result = $this->execute($method, $resource, array('limit'=>$limit));

        if ($result['code'] != 200)
            throw new Exception($this->createExceptionMessage($result));

        if ($result == null || $result['body'] == null)
            return array();

        $rows = explode("\n", trim($result['body']));

        $records = array();

        if (count($rows) > 1) {
            $headers = explode(',', trim($rows[0]));
            $records = $this->parseCsv($headers, $rows, 1, count($rows)-1);
        }

        return $records;
    }
    
    /**
     * Gets an arbitrary resource as raw data.
     *
     * @param string $resource the resource to get, e.g., /logs/400.txt or /users/1/recommendations.csv
     * @exception Exception when the request failed due to clientside or serverside problems
     * @return string
     */
	public function getResource($resource) {
    	
    	$method = 'GET';
    	$result = $this->execute($method, $resource, array());
    	
    	if ($result['code'] != 200)
            throw new Exception($this->createExceptionMessage($result));

        if ($result == null || $result['body'] == null)
            return '';
            
        return $result['body'];
    }

    /**
     * signs and executes the request
     * @return array ('code'=>int, 'headers'=>array(), 'body'=>string)
     */
    protected function execute($method, $resource, $params=array(), $body=null) {

        //remove empty entries from the fields
        $params = $this->removeEmptiesFromArray($params);

        $options = array(
                'consumer_key' => $this->settings->account,
                'consumer_secret' => $this->settings->secretkey
        );

        OAuthStore::instance('2Leg', $options);

        try {

            //print_r($params);
            $url = $this->settings->base_url . '/sites/' . $this->settings->account . $resource;

            $request = new OAuthRequester($url, $method, $params);
            $result = $request->doRequest();

            return $result;

        } catch(OAuthException2 $e) {
            error_log($e->getMessage());
            //throw new Exception('Error. ' . $e->getMessage());
            return array();
        }
    }

    private function parseRecommendationsOrSimilarItems($result) {

        if ($result == null || $result['body'] == null) {
            return array();
        }

        $rows = explode("\n", trim($result['body']));

        $recommendations = array();

        if (count($rows) > 0) {
            $headers = array('itemid', 'score', 'algorithm', 'certainty');
            $recommendations = $this->parseCsv($headers, $rows, 0, count($rows)-1);
        }

        return $recommendations;

    }

    private function parseCsv($headers, $rows, $from, $to) {

        $records = array();

        for ($i=$from; $i<=$to; $i++) {

            $values = explode(',', trim($rows[$i]));
            $record = array();

            for ($j=0; $j<count($headers); $j++) {
                if (isset($values[$j]) && strlen($values[$j]) > 0)
                    $record[$headers[$j]] = $values[$j];
            }

            $records[] = $record;
        }

        return $records;

    }

    private function removeEmptiesFromArray($array=array()) {

        foreach ($array as $key => $value) {
            if (is_null($value) || $value == "") {
                unset($array[$key]);
            }
        }

        return $array;
    }

    private function createExceptionMessage($result=array()) {
        
        $message = 'Server response code ' . $result['code'];

        if ($result['body'])
            $message .= ': ' . $result['body'];
        
        return $message;
    }

}

?>
