<?php

/**
 * 
 * Month Converter
 *
 **/

class WeekHelper{

    /**
     *
     *  Get Start Date From Week Number of Years
     *  
     **/
    function getStartAndEndDate($week, $year)
    {

        $time = strtotime("1 January $year", time());
        $day = date('w', $time);
        $time += ((7*$week)+1-$day)*24*3600;
        $return = date('Y-n-j', $time);
        // $time += 6*24*3600;
        // $return[1] = date('Y-n-j', $time);
        return $return;
    }
    
    /**
     *
     *  Get Week Of Month from Date
     *  
     **/
    function weekOfMonth($date) {
        //$date = date('Y-m-d', strtotime(str_replace('-','/', $date)));
        list($y, $m, $d) = explode('-', date('Y-m-d', strtotime($date)));
        $date = date(strtotime("$y-$m-$d"));
        var_dump("tanggal ".$date);
        //Get the first day of the month.
        $firstOfMonth = strtotime(date("Y-m-01", $date));
        //Apply above formula.
        return (intval(date("W", $date))) - (intval(date("W", $firstOfMonth)) + 1) ;
    }

}
?>
