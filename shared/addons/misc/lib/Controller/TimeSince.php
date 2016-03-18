<?php
// Taken from http://stackoverflow.com/questions/4394161/php-time-calculation
// Credit to El Yobo

namespace misc;
class Controller_TimeSince extends \AbstractController {
    function timeSince($dateFrom, $dateTo) {
        // array of time period chunks
        $chunks = array(
            array(60 * 60 * 24 * 365 , 'year'),
            array(60 * 60 * 24 * 30 , 'month'),
            array(60 * 60 * 24 * 7, 'week'),
            array(60 * 60 * 24 , 'day'),
            array(60 * 60 , 'hour'),
            array(60 , 'minute'),
        );

        $original = strtotime($dateFrom);
        $now      = strtotime($dateTo);
        $since    = $now - $original;
        $message  = ($now < $original) ? '-' : null;

        // If the difference is less than 60, we will show the seconds difference as well
        if ($since < 60) {
            $chunks[] = array(1 , 'second');
        }

        // $j saves performing the count function each time around the loop
        for ($i = 0, $j = count($chunks); $i < $j; $i++) {

            $seconds = $chunks[$i][0];
            $name = $chunks[$i][1];

            // finding the biggest chunk (if the chunk fits, break)
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }

        $print = ($count == 1) ? '1 ' . $name : $count . ' ' . $name . 's';

        if ($i + 1 < $j) {
            // now getting the second item
            $seconds2 = $chunks[$i + 1][0];
            $name2 = $chunks[$i + 1][1];

            // add second item if it's greater than 0
            if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
                $print .= ($count2 == 1) ? ', 1 ' . $name2 : ', ' . $count2 . ' ' . $name2 . 's';
            }
        }
        return $message . $print;
    }

}
