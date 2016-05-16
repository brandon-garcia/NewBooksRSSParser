<?php

namespace cn\processor;

class CD extends AbstractProcessor {

    public function __construct($cn,$location) {
        $this->location = strtolower($location);
        $this->data = array('classification_type'=>'CD',
            'subject' => $this->getSubject(),
	    'location' => $location);
    }

    public function matches($range) {
        return FALSE;
    }

    protected function compareTo($cn) {
        return -2; // invalid
    }

    public function getSubject() {
        $subjects = array();

        if (stripos($this->location,"music library")!==FALSE)
            $subjects[] = "Music";

        return implode(', ',$subjects);
    }

}
