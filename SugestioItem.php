<?php

/**
 * This class holds the metadata for Items. Member fields are exposed rather than
 * having a getter and setter for each.
 *
 * See the API documentation for more information on individual fields.
 */
class SugestioItem {

    public $id;

    public $description_short;
    public $description_full;
    public $permalink;

    public $available;
    public $from;
    public $until;

    public $location_simple;
    public $location_latlong;

    public $category;
    public $creator;
    public $segment;
    public $tag;


    /**
     * Creates a new Item object.
     * @param string $id the item id
     */
    public function __construct($id) {
        $this->id = $id;
        $this->category = array();
        $this->creator = array();
        $this->segment = array();
        $this->tag = array();
    }

    /**
     * Returns an associative array containing all the member variables
     * as name=>value pairs. Used internally by SugestioClient when issuing
     * the addItem webservice call.
     * @return array(mixed)
     */
    public function getFields() {

        $fields = array();

        $fields['id'] = $this->id;

        $fields['description_short'] = $this->description_short;
        $fields['description_full'] = $this->description_full;
        $fields['permalink'] = $this->permalink;

        $fields['available'] = $this->available;
        $fields['from'] = $this->from;
        $fields['until'] = $this->until;

        $fields['location_simple'] = $this->location_simple;
        $fields['location_latlong'] = $this->location_latlong;

        $fields['category[]'] = $this->category;
        $fields['creator[]'] = $this->creator;
        $fields['segment[]'] = $this->segment;
        $fields['tag[]'] = $this->tag;

        return $fields;
    }

}

?>
