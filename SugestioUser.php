<?php

/**
 * This class holds the metadata for Users. Member fields are exposed rather than
 * having a getter and setter for each.
 *
 * See the API documentation for more information on individual fields.
 */
class SugestioUser {

    public $id;

    public $gender;
    public $birthday;

    public $location_simple;
    public $location_latlong;

    public $apml;
    public $foaf;


    /**
     * Creates a new User object.
     * @param string $id the user id
     */
    public function __construct($id) {
        $this->id = $id;
    }

    /**
     * Returns an associative array containing all the member variables
     * as name=>value pairs. Used internally by SugestioClient when issuing
     * the addUser webservice call.
     * @return array(mixed)
     */
    public function getFields() {

        $fields = array();

        $fields['id'] = $this->id;

        $fields['gender'] = $this->gender;
        $fields['birthday'] = $this->birthday;

        $fields['location_simple'] = $this->location_simple;
        $fields['location_latlong'] = $this->location_latlong;

        $fields['apml'] = $this->apml;
        $fields['foaf'] = $this->foaf;

        return $fields;
    }

}

?>
