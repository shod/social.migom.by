<?php

class SiteService {

    public static function timeToDate($time, $day = false)
    {
        if($day){
            $result = date("Y-m-d", $time);
        } else {
            $result = date("Y-m-d H:i", $time);
        }
        return $result;
    }

    public static function arrayTranslate($template, &$array)
    {
        $result = array();
        foreach($array as $key => $val)
        {
            $result[$key] = Yii::t($template, $val);
        }
        return $result;
    }

    public static function subStrEx($str, $len)
    {
        if(strlen($str) <= $len){
            return $str;
        }
        return mb_substr($str, 0, $len, 'utf8').'&hellip;';
    }

    public static function getCorectWordsT($template, $word, $number)
    {
        $words = Yii::t($template, $word);
        $wArr = explode('|', $words);

//        $number = substr((string)$number, -2);

        $c = $number % 10;
        if ($number > 10 && $number < 20)
            return $wArr[1];
        if ($c == 1)
            return $wArr[0];
        if ($c > 1 && $c <= 4)
            return $wArr[2];
        if ($c > 4 || $c == 0)
            return $wArr[1];
    }

    public static function timeRange($from, $to) {
        $differenceFull  = $to - $from;
        $differenceYear  = floor(($differenceFull) /32140800);
        $differenceMonth = floor(($differenceFull) /2678400);
        $differenceDay   = floor(($differenceFull) /86400);
        $differenceHour  = floor(($differenceFull) /3600);
        $differenceMin   = floor(($differenceFull) /60);
        $differenceSec   = $differenceFull;

        if($differenceFull <= 10){
            $differenceSec = 1;
        }
        if ($differenceYear >= 1){
            return $differenceYear . ' ' . self::getCorectWordsT('Site', 'yaer', $differenceYear);
        }
        if ($differenceMonth >= 1){
            return $differenceMonth . ' ' . self::getCorectWordsT('Site', "month", $differenceMonth);
        }
        if ($differenceDay >= 1){
            return $differenceDay . ' ' . self::getCorectWordsT('Site', "day", $differenceDay);
        }
        if ($differenceHour >= 1){
            return $differenceHour . ' ' . self::getCorectWordsT('Site', "hour", $differenceHour);
        }
        if ($differenceMin >= 1){
            return $differenceMin . ' ' . self::getCorectWordsT('Site', "minute", $differenceMin);
        }
        if ($differenceSec < 60){
            return $differenceSec . ' ' . self::getCorectWordsT('Site', "second", $differenceSec);
        }
    return $from;
    }
}
