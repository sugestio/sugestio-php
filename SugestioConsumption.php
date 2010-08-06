<?php

/**
 * This class holds the metadata for Consumptions. Member fields are exposed rather than
 * having a getter and setter for each.
 *
 * See the API documentation for more information on individual fields.
 */
class SugestioConsumption {

    public $itemid;
    public $userid;

    public $type;
    public $detail;
    public $date;

    public $location_simple;
    public $location_latlong;

    public $extra;

    /**
     * Creates a new Consumption object.
     * @param string $userid the id of the user who consumed the item
     * @param string $itemid the id of the item that was consumed
     */
    public function __construct($userid, $itemid) {
        $this->userid = $userid;
        $this->itemid = $itemid;
        $this->extra = array();
    }

    /**
     * Returns an associative array containing all the member variables
     * as name=>value pairs. Used internally by SugestioClient when issuing
     * the addConsumption webservice call.
     * @return array(mixed)
     */
    public function getFields() {

        $fields = array();

        $fields['userid'] = $this->userid;
        $fields['itemid'] = $this->itemid;

        $fields['type'] = $this->type;
        $fields['detail'] = $this->detail;
        $fields['date'] = $this->date;

        $fields['location_simple'] = $this->location_simple;
        $fields['location_latlong'] = $this->location_latlong;

        foreach ($this->extra as $key => $value) {
            $fields[$key] = $value;
        }

        return $fields;
    }

}

?>
