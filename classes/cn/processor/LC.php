<?php

namespace cn\processor;

class LC extends AbstractProcessor {
    public function __construct($number,$location) {
        if (is_array($number))
            var_dump($number);

        $this->cn = $this->normalize($number);
        $this->data = array('classification_type'=>'LC',
            'subject' => $this->getSubject(),
	    'location' => $location);
    }

    public function getSubject() {
        $subjects = array();

        if ($this->matches("GA-GC,GE-GF,GN,GR,GT,GV"))
            $subjects[] = "Anthropology";

        if ($this->matches('QM,QP,RA'))
            $subjects[] = "Applied Heath Science";

        if ($this->matches("N,TR"))
            $subjects[] = "Art";

        if ($this->matches("BL-BV1999,BV4000-BV4399,BX"))
            $subjects[] = "Biblical & Theological Studies";

        if ($this->matches('QH,QR,S'))
            $subjects[] = "Biology";

        if ($this->matches("HB,HC,HG,HJ,K,HD1-HD1395.5,HD2321-HD9999,HF5001-HF6182"))
            $subjects[] = "Business & Economics";

        if ($this->matches("QD"))
            $subjects[] = "Chemistry";

        if ($this->matches("BV4400-BV5099"))
            $subjects[] = "Christian Formation & Ministry";

        if (false)
            $subjects[] = "Communication";

        if ($this->matches('QA75.5,QA76.765'))
            $subjects[] = "Computer Science";

        if ($this->matches("L,PZ"))
            $subjects[] = "Education";

        if ($this->matches('TA,TN'))
            $subjects[] = "Engineering";

        if ($this->matches('PE,PN,PQ-PT'))
            $subjects[] = "English";

        if ($this->matches('GE,QE38,QC882-QC994.9,QH72-QH77,S,TD169-TD1066'))
            $subjects[] = "Environmental Science";

        if ($this->matches('PA-PD,PJ'))
            $subjects[] = "Foreign Languages";

        if ($this->matches("QE"))
            $subjects[] = "Geology";

        if ($this->matches('C-G'))
            $subjects[] = "History";

        if ($this->matches('HC,HD'))
            $subjects[] = "HNGR";

        if ($this->matches('BV2000-BV3999'))
            $subjects[] = "Intercultural Studies";

        if ($this->matches('QA'))
            $subjects[] = "Mathematics";

        if ($this->matches('M,ML,MT'))
            $subjects[] = "Music";

        if ($this->matches('BA-BD,BH,BJ'))
            $subjects[] = "Philosophy";

        if ($this->matches('QB-QC'))
            $subjects[] = "Physics";

        if ($this->matches('J,K'))
            $subjects[] = "Politics & International Relations";

        if ($this->matches('BF,QP351-QP495,R726.5-R726.8,RC321-RC571'))
            $subjects[] = "Psychology";

        if ($this->matches('HM-HX'))
            $subjects[] = "Sociology";

        if ($this->matches('HT101-HT395'))
            $subjects[] = "Urban Studies";

        return implode(', ',$subjects);
    }

    protected function compareTo($class) {
        $n = count($class);

        for ($i=0; $i<$n; $i++) {
            if ($this->cn[$i] !== $class[$i] && $class[$i]!=='*') {
                return ( ord($this->cn[$i]) < ord($class[$i] ))?-1:1;
            }
        }
        return 0;
    }

    public function matches($rangeList) {
        foreach ( \util\Utility::parseRange($rangeList) as $range ) {
            $range = self::normalize_range($range);

            if (count($range)===1) {

                if ($this->equals($range[0]))
                    return true;

            } else {

                $min = $range[0];
                $max = $range[1];

                if ($this->equals($min)
                || $this->equals($max)
                ||($this->greaterThan($min) && $this->lessThan($max))
                ) {
                    return true;
                }

            }
        }

        return false;
    }

    private static function normalize_range(array $range) {
        $n = count($range);
        for ($i=0; $i<$n; ++$i) {
            $range[$i] = self::normalize($range[$i]);
        }
        return $range;
    }

    private static function normalize($cn) {
        $cn_orig = $cn;

        $regex_alpha = "([A-Z]{1,3})";
        $regex_num = "([0-9]{0,4})";
        $regex_extra = "([ ]?\.[A-Z0-9][0-9]*)";
        $regex = "/^{$regex_alpha}{$regex_num}?{$regex_extra}?/";

        $matches = array();
        if (preg_match($regex,$cn,$matches)) {

            //normalize alpha
            $alpha = $matches[1];//.\util\Utility::ntimes('*',3-strlen($matches[1]));

            //normalize num
            if (array_key_exists(2, $matches))
                $num = $matches[2].\util\Utility::ntimes('*',4-strlen($matches[2]));
            else
                $num = "****";

            //normalize extra
            if (array_key_exists(3, $matches)) {
                $extra_match = array();
                if (preg_match("/([A-Z])/",$matches[3],$extra_match)) {
                    $ch = $extra_match[0];
                    $i = 2;
                } else {
                    $ch = '*';
                    $i = 1;
                }
                $extra = $ch.substr($matches[3],$i);
            } else {
                $extra = "";
            }

            return str_split($alpha.$num.$extra);
        }

        error_log("failed to normalize: $cn_orig");
        return;
    }
}
