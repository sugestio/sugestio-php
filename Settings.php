<?php

/**  
 * This is where you enter your access credentials. 
 */
class Settings {

    /**
     * The URL of the Sugestio webservice
     */
    public $base_url = 'http://api.sugestio.com';

    /**
     *  The account name of the project that wishes to communicate with the webservice
     */
    public $account = 'sandbox';

    /**
     * The secret key that will be used to authenticate the communication with the webservice
     */
    public $secretkey = 'demo';

}
?>