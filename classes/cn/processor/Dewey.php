<?php

namespace cn\processor;

class Dewey extends AbstractProcessor {
    public function __construct($number,$location) {
        $this->cn = $number + 0.0;
        $this->data = array('classification_type'=>'Dewey',
            'subject' => $this->getSubject(),
	    'location' => $location
	    );
    }

    public function getSubject() {
        $subjects = array();

        if ($this->matches("300-301.999,306-306.999"))
            $subjects[] = "Anthropology";

        if ($this->matches("611-616.999"))
            $subjects[] = "Applied Health Science";

        if ($this->matches("700-770.999"))
            $subjects[] = "Art";

        if ($this->matches("200-299.999"))
            $subjects[] = "Biblical & Theological Studies";

        if ($this->matches('570-599.999'))
            $subjects[] = "Biology";

        if ( $this->matches('330-349.999,657-659.999,406-406.999,506-506.999,650-650.999,706-706.999,906-906.999'))
            $subjects[] = "Business & Economics";

        if ($this->matches('540-548.999'))
            $subjects[] = "Chemistry";

        if ($this->matches('230-236.999,238-243.999,246-249.999,262-265.999,268-287.999'))
            $subjects[] = "Christian Formation & Ministry";

        if ($this->matches('70-79.999,302-302.999,383-383.999,384-384.999,791-792.999,800-800.999,808-808.999'))
            $subjects[] = "Communication";

        if ($this->matches('3-6.999'))
            $subjects[] = "Computer Science";

        if ($this->matches('370-378.999'))
            $subjects[] = "Education";

        if ($this->matches('620-629.999'))
            $subjects[] = "Engineering";

        if ($this->matches('800-829.999'))
            $subjects[] = "English";

        if (false)
            $subjects[] = "Environmental Science";

        if (false)
            $subjects[] = "Foreign Languages";

        if ($this->matches('550-560.999'))
            $subjects[] = "Geology";

        if ($this->matches('900-999.999'))
            $subjects[] = "History";

        if (false)
            $subjects[] = "HNGR";

        if ($this->matches("266-266.999"))
            $subjects[] = "Intercultural Studies";

        if ($this->matches('500-519.999'))
            $subjects[] = "Mathematics";

        if ($this->matches('780-789.999'))
            $subjects[] = "Music";

        if ($this->matches('100-129.999,140-149.999,160-199.999'))
            $subjects[] = "Philosophy";

        if ($this->matches('520-539.999'))
            $subjects[] = "Physics";

        if ($this->matches('310-329.999'))
            $subjects[] = "Politics & International Relations";

        if ($this->matches('150-159.999,616-616.999'))
            $subjects[] = "Psychology";

        if ($this->matches('300-309.999'))
            $subjects[] = "Sociology";

        if ($this->matches('307-307.999'))
            $subjects[] = "Urban Studies";

        return implode(', ',$subjects);
    }

    protected function compareTo($class) {
        if ($this->cn<$class) {
            return -1;
        } else if ($this->cn==$class) {
            return 0;
        } else {
            return 1;
        }
    }

    public function matches($range) {
        foreach ( \util\Utility::parseRange($range) as $r ) {
            if (count($r)===1) {
		//error_log("Testing subject with one number in range, the matched number was $r[0] and the call number was $this->cn");
                if ($this->equals($r[0]))
                    return true;
            } else if ($this->greaterThan($r[0]) && $this->lessThan($r[1])) {
		//error_log("Testing subject with hyphenated ranges, the matched number was $r[0] and the call number was $this->cn");
                return true;
            }
        }
        return false;
    }
}
