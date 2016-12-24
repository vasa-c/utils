<?php

class TourneyCalendar
{
    /**
     * @param int $count
     * @param bool $shuffle [optional]
     * @return array
     * @throws \LogicException
     */
    public static function create($count, $shuffle = true) 
    {
        if (isset(self::$cache[$count])) {
            $result = self::$cache[$count];
            if ($shuffle) {
                shuffle($result);
            }
            return $result;
        }
        $result = self::getPre($count);
        if ($result === null) {
            if ((($count % 2) === 0) && ($count > 2)) {
                $half = (int)($count / 2);
                $part = self::create($half, false);
                if ($part === null) {
                    throw new \LogicException('Count '.$count.' not supported');
                }
                $result = self::createHalf($part, $half);
            } else {
                $result = self::create($count + 1, false);
                if ($result === null) {
                    throw new \LogicException('Count '.$count.' not supported');
                }
                $result = self::createParent($result, $count);
            }
        }        
        self::$cache[$count] = $result;
        if ($shuffle) {
            shuffle($result);
        }
        return $result;
    }
    
    /**
     * @param array $calendar
     * @return array
     */
    public static function homeAway(array $calendar)
    {
        $homes = [];
        foreach ($calendar as &$tour) {
            foreach ($tour as &$pair) {
                foreach ($pair as $p) {
                    if (!isset($homes[$p])) {
                        $homes[$p] = 0;
                    }
                }
                $h = $pair[0];
                $a = $pair[1];                
                if ($homes[$h] > $homes[$a]) {
                    $pair = array_reverse($pair);
                    $homes[$a]++;
                    $homes[$h]--;
                } else {
                    $homes[$a]--;
                    $homes[$h]++;
                }
            }
            unset($pair);
        }
        unset($tour);
        return $calendar;
    }
    
    /**
     * @param array $calendar
     * @param string $m [optional]
     * @param string $p [optional]
     * @param string $t [optional]
     * @return string
     */
    public static function format(array $calendar, $m = '-', $p = ',', $t = ';')
    {
        foreach ($calendar as &$tour) {
            foreach ($tour as &$pair) {
                $pair = implode($m, $pair);
            }
            unset($pair);
            $tour = implode($p, $tour);
        }
        unset($tour);        
        return implode($t, $calendar);
    }
    
    /**
     * @param array $calendar
     * @return array
     */
    public static function reverse(array $calendar)
    {
        foreach ($calendar as &$tour) {
            foreach ($tour as &$pair) {
                $pair = array_reverse($pair);
            }
            unset($pair);
        }
        unset($tour);
        return $calendar;
    }
    
    /**
     * @param array $part
     * @param int $half
     * @return array
     */
    private static function createHalf(array $part, $half)
    {
        $result = [];
        foreach ($part as $tour) {            
            foreach ($tour as $pair) {
                $pair[0] += $half;
                $pair[1] += $half;
                $tour[] = $pair;
            }
            $result[] = $tour;
        }
        $count = $half * 2;
        for ($t = 1; $t <= $half; $t++) {
            $tour = [];
            for ($i = 0; $i < $half; $i++) {
                $j = $i + $t;
                if ($j > $half) {
                    $j %= $half;
                }
                $tour[] = [$i + 1, $j + $half];
            }
            $result[] = $tour;
        }
        return $result;
    }
    
    /**
     * @param array $parent
     * @param int $count
     * @return array
     */
    private static function createParent(array $parent, $count)
    {
        $result = [];
        foreach ($parent as $tour) {
            $new = [];
            foreach ($tour as $pair) {
                if (($pair[0] <= $count) && ($pair[1] <= $count)) {
                    $new[] = $pair;
                }
            }
            $result[] = $new;
        }
        return $result;
    }
    
    /**
     * @param int $count
     * @return array
     */
    private static function getPre($count)
    {
        if (!isset(self::$pre[$count])) {
            return null;
        }
        $pre = self::$pre[$count];
        if (is_array($pre)) {
            $pre;
        }
        $result = [];
        foreach (explode(';', $pre) as $t) {
            $tour = [];
            foreach (explode(',', $t) as $p) {
                $tour[] = array_map('intval', explode('-', $p));
            }
            $result[] = $tour;
        }
        return $result;
    }

    /**
     * @var array
     */
    private static $pre = [
        2 => '1-2',
        3 => '1-2;1-3;2-3',
        6 => '1-6,2-5,3-4;1-2,3-5,4-6;1-3,2-6,4-5;1-4,2-3,5-6;1-5,2-4,3-6',
    ];
    
    /**
     * @var array
     */
    private static $cache = [];
}

