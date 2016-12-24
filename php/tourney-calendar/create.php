<?php

require_once __DIR__.'/TourneyCalendar.php';

for ($i = 2; $i <= 30; $i++) {
    echo '# '.$i.PHP_EOL.PHP_EOL;
    $calendar = TourneyCalendar::create($i);
    $calendar = TourneyCalendar::homeAway($calendar);
    echo TourneyCalendar::format($calendar, '-', ' ', PHP_EOL);    
    echo PHP_EOL.PHP_EOL;
}

