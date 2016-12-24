<?php

require_once __DIR__.'/TourneyCalendar.php';

class TourneyCalendarTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerCreate
     */
    public function testCreate($count)
    {
        $result = TourneyCalendar::create($count, false);             
        $this->assertInternalType('array', $result);
        $this->assertGreaterThanOrEqual($count - 1, count($result));
        $limit = ($count > 16) ? 2 : 1;
        $this->assertLessThanOrEqual($count + $limit, count($result));
        $teams = [];
        foreach ($result as $noTour => $tour) {
            $this->assertInternalType('array', $tour);
            $half = (int)($count / 2);
            $limit = ($count > 16) ? 3 : 1;            
            $this->assertGreaterThanOrEqual($half - $limit, count($tour));
            $this->assertLessThanOrEqual($half + 1, count($tour));
            $tourK = 't_'.$noTour;
            foreach ($tour as $noPair => $pair) {
                $this->assertInternalType('array', $tour);
                $this->assertCount(2, $pair);
                $this->assertSame([0, 1], array_keys($pair));
                $this->assertNotSame($pair[0], $pair[1]);
                foreach ($pair as $no => $i) {
                    $this->assertInternalType('integer', $i);
                    $this->assertGreaterThanOrEqual(1, $i);
                    $this->assertLessThanOrEqual($count, $i);
                    $opp = $pair[1 - $no];
                    if (!isset($teams[$i])) {
                        $teams[$i] = ['matches' => 0];
                    }
                    $teams[$i]['matches']++;
                    if (isset($teams[$i][$tourK])) {
                        $this->fail('Team #'.$i.' more than 1 match in tour #'.$noTour);
                    }
                    $teams[$i][$tourK] = true;
                    if (isset($teams[$i][$opp])) {
                        $this->fail('Team #'.$i.' more than 1 match with opponent #'.$opp);
                    }
                    $teams[$i][$opp] = true;
                }
            }
        }
        $this->assertCount($count, $teams);
        foreach ($teams as $k => $t) {
            $this->assertSame($count - 1, $t['matches'], 'Count matches of #'.$k);
        }
        $result = TourneyCalendar::homeAway($result);
        $homes = [];
        foreach ($result as $noTour => $tour) {
            foreach ($tour as $pair) {
                foreach ($pair as $no => $i) {
                    if (!isset($homes[$i])) {
                        $homes[$i] = [0, 0];
                    }
                    if ($no === 0) {
                        $homes[$i][0]++;
                    } else {
                        $homes[$i][1]++;
                    }
                    $h = $homes[$i];
                    $message = 'Delta of #'.$i.' for tour #'.$noTour.' ('.implode('-', $h).')';
                    $delta = 3; // + (int)($noTour / 10);
                    $this->assertLessThanOrEqual($delta, abs($h[0] - $h[1]), $message);
                }
            }
        }
    }
    
    public function testReverse()
    {
        $calendar = TourneyCalendar::create(6);
        $pair1 = $calendar[3][2];
        $calendar = TourneyCalendar::reverse($calendar);
        $pair2 = $calendar[3][2];
        $this->assertSame(array_reverse($pair1), $pair2);
    }
    
    /**
     * @return array
     */
    public function providerCreate()
    {
        $data = [];
        foreach (range(2, 16) as $count) {
            $data[$count] = [$count];
        }
        return $data;
    }
}

