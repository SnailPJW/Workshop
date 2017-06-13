<?php
class DateHelper{
    public static function verifyYearMonth($yearmonth, &$year, &$month){
        if(strpos($yearmonth, '-')!==false){
            list($year, $month) = explode('-', $yearmonth);
            $year = intval($year);
            $month = intval($month);
            if($year<=0 || $month>13 || $month<1) return false;
            else return true;
        }else return false;
    }
    public static function validateDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') == $date;
    }
    //checks if the date is within two days
    public static function isWithinTwoDays($date){
        $cur_date = strtotime(date('Y-m-d').' 00:00:00');
        $last_two_date = strtotime('-2 day', $cur_date);
        $date_date = strtotime($date);
        //return  $last_two_date <= $date_date;
        return true;
    }
}