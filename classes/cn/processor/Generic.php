<?php

namespace cn\processor;

class Generic extends AbstractProcessor {
    public function __construct($segments,$location) {
        $txt = "";
        foreach($segments as $seg) {
            if ($txt==='')
                $txt = "{ $seg";
            else
                $txt .= ", $seg";
        }
        $txt .= "}";
        $this->data = array('classification_type'=>"UNKNOWN",
            'UNKNOWN'=>$txt,
	    'location'=>$location
	    );

    }

    public function getSubject() {
        return "";
    }

    public function matches($range) {
        return FALSE;
    }

    protected function compareTo($cn) {
        return 2; //invalid
    }
}
