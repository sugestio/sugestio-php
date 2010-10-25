<?php

/**
 * This class holds the metadata for Consumptions. Member fields are exposed rather than
 * having a getter and setter for each. See the API documentation for more information 
 * on individual fields.
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
