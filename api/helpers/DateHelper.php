<?php

namespace api\helpers;

use DateTime;
use DateTimeZone;

/**
 * Class DateHelper
 *
 * This class is responsible for handle date operations
 *
 * @package api\helpers
 */
class DateHelper
{
    //!*****************************************************************************
    // DATE AND TIME MANIPULATION FUNCTIONS
    //!*****************************************************************************

    /**
     * Function to format date dd/mm/yy to yyyy-mm-dd
     *
     * @param date_ => base date
     *
     * @return string
     */
    public static function dd_mm_yy_to_yyyy_mm_dd($date_)
    {
        $return_date = "";

        if (substr($date_, 6, 2) >= 50)
            $return_date = "19" . substr($date_, 6, 2) . "-" . substr($date_, 3, 2) . "-" . substr($date_, 0, 2);
        else
            $return_date = "20" . substr($date_, 6, 2) . "-" . substr($date_, 3, 2) . "-" . substr($date_, 0, 2);

        return $return_date;
    }

    /**
     * Function to handle operations with time in minutes and seconds (mm:ss)
     * @time01_ => operand 01
     * @time02_ => operand 02
     * @op_    => operator
     */
    public static function min_sec_operation($time01_, $time02_, $op_)
    {
        // Auxiliary variable
        $total = 0;

        // Check the time format
        if (!strpos($time01_, ":")) {
            $time01_ .= ":00";
        }

        // Split the values by :
        $time01_ = explode(':', $time01_);
        $total += $time01_[0] * 60;

        // Sum up the total time in seconds
        if (isset($time01_[1]))
            $total += $time01_[1];
        else
            $total = 0;

        // Check the time format
        if (!strpos($time02_, ":")) {
            $time02_ .= ":00";
        }

        // Split the values by :
        $time02_ = explode(':', $time02_);

        // Check the operation
        switch ($op_) {
            case "+":
                $total = $total + ($time02_[0] * 60);
                break;
            case "-":
                $total = $total - ($time02_[0] * 60);
                break;
            case "*":
                $total = $total * ($time02_[0] * 60);
                break;
            case "/":
                $total = $total / ($time02_[0] * 60);
                break;
        }

        // Check the operation to perform
        if (isset($time02_[1])) {
            switch ($op_) {
                case "+":
                    $total = ($total + $time02_[1]);
                    break;
                case "-":
                    $total = ($total - $time02_[1]);
                    break;
                case "*":
                    $total = ($total * $time02_[1]);
                    break;
                case "/":
                    //$total = ($total / $time02_[1]); break;
            }
        }

        // Convert to mm:ss
        $mins = $total / 60;
        $secs = abs($total % 60);

        // Change the second format
        if ($secs < 10)
            $secs = "0" . $secs;

        // Merge minutes and seconds
        $total = intval($mins) . ':' . $secs;

        // Return the total
        return $total;
    }

    /**
     * Sum the time in minutes and seconds (mm:ss)
     * @value01 => value 01 to be summed
     * @value02 => value 02 to be summed
     */
    public static function min_sec_sum($value01, $value02)
    {
        // Auxiliary variable
        $total = 0;

        // Split the values by :
        $value01 = explode(':', $value01);
        $total += $value01[0] * 60;
        if (isset($value01[1]))
            $total += $value01[1];
        else
            $total = 0;

        // Split the values by :
        $value02 = explode(':', $value02);
        $total += $value02[0] * 60;
        if (isset($value02[1]))
            $total += $value02[1];
        else
            $total = 0;

        // Convert to mm:ss
        $mins = $total / 60;
        $secs = $total % 60;

        // Change the second format
        if ($secs < 10)
            $secs = "0" . $secs;

        // Return the total
        return intval($mins) . ':' . $secs;
    }

    /**
     * Sum the time in minutes and seconds (mm:ss)
     * @array_minutes_ => array with minutes to be summed
     */
    public static function min_sec_sum_array($array_minutes_)
    {
        // Auxiliary variables
        $aux_duration = 0;
        $aux_duration_value = "";
        $array_length = sizeof($array_minutes_);

        // Check if the array contain values
        if ($array_length > 0) {
            // Run through the array
            for ($i = 0; $i < $array_length; $i++) {
                if (isset($array_minutes_[$i]) && strcmp($array_minutes_[$i], "") != 0) {
                    // Set the duration auxiliar variable
                    $aux_duration_value = $array_minutes_[$i];

                    // Check the time type
                    if (strpos($aux_duration_value, "m") === false) {
                        // Subtotal duration
                        $aux_duration = min_sec_sum($aux_duration, $aux_duration_value);
                    } else {
                        // Subtotal duration
                        $aux_duration = min_sec_sum($aux_duration, format_min_sec($aux_duration_value));
                    }
                }
            }

            // Function return
            return $aux_duration;
        }
    }

    /**
     * Convert the time (mm:ss) into seconds
     * @value_ => value to be converted
     */
    public static function min_sec_tosec($value_)
    {
        // Auxiliary variable
        $total = 0;

        // Check the time format
        if (!strpos($value_, ":")) {
            $value_ .= ":00";
        }

        // Split the values by :
        $value01 = explode(':', $value_);
        $total += $value01[0] * 60;
        if (isset($value01[1]))
            $total += $value01[1];
        else
            $total = 0;

        // Return the total
        return intval($total);
    }

    /**
     * Function that converts XXmYYs to mm:ss
     * @time_ => value 01 to be formatted
     */
    public static function format_min_sec($time_)
    {
        // Replace strings
        $time_ = str_replace("m", ":", $time_);
        $time_ = str_replace("s", "", $time_);
        $time_ = trim($time_);

        // Return the total
        return $time_;
    }

    /**
     * Function that converts mm:ss to XXmYYs
     * @time_ => value 01 to be formatted
     */
    public static function format_mm_ss($time_)
    {
        // Format and integer value into a time format
        if (!strpos($time_, ":") && !strpos($time_, "m") && !strpos($time_, "s")) {
            $time_ .= ":00";
        }

        // Test if it's necessary add a zero at the beginning
        $aux = explode("m", $time_);

        // Add zero (when it's required)
        if ($aux[0] < 10) {
            if (substr($aux[0], 0, 1) != "0") {
                $time_ = "0" . $time_;
            }
        }

        // Replace strings
        $time_ = str_replace(":", "m", $time_);
        $time_ = trim($time_);
        $time_ .= "s";

        // Return the total
        return $time_;
    }

    /**
     * Function that converts hh:mm:ss to mm:ss
     * @value01 => value 01 to be formatted
     */
    public static function format_hh_mm_ss($value01)
    {
        // Check the string length
        if (strlen($value01) > 5) {
            // Divide the value in parts
            $aux = explode(":", $value01);

            // Add the equivalent hours to the minutes
            $aux[1] += ($aux[0] * 60);

            // Add zero
            if ($aux[1] < 10) {
                if (substr($aux[1], 0, 1) != "0")
                    $aux[1] = "0" . $aux[1];
            }

            // Concatenate the result
            $value01 = $aux[1] . ":" . $aux[2];
        }

        // Return the total
        return $value01;
    }

    /*
     * A mathematical decimal difference between two informed dates
     *
     * Author: Sergio Abreu
     * Website: http://sites.sitesbr.net
     *
     * Features:
     * Automatic conversion on dates informed as string.
     * Possibility of absolute values (always +) or relative (-/+)
    */
    public static function dateDifference($str_interval, $dt_menor, $dt_maior, $relative = false)
    {
        if (is_string($dt_menor)) $dt_menor = date_create($dt_menor);
        if (is_string($dt_maior)) $dt_maior = date_create($dt_maior);

        $diff = date_diff($dt_menor, $dt_maior, !$relative);

        switch ($str_interval) {
            case "y":
                $total = $diff->y + $diff->m / 12 + $diff->d / 365.25;
                break;
            case "m":
                $total = $diff->y * 12 + $diff->m + $diff->d / 30 + $diff->h / 24;
                break;
            case "d":
                $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h / 24 + $diff->i / 60;
                break;
            case "h":
                $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i / 60;
                break;
            case "i":
                $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s / 60;
                break;
            case "s":
                $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i) * 60 + $diff->s;
                break;
        }

        if ($diff->invert)
            return -1 * $total;
        else
            return $total;
    }

    /**
     * Function to return the previous month (E.g: 07/2016 -> 06/2016 )
     * @ref_month_ => references's month (format: MM/YYYY)
     */
    public static function previousMonth($ref_month_)
    {
        // Complete the reference month
        $ref_date = "01/" . $ref_month_;

        // Converts to american format
        $ref_date = str_replace('/', '-', $ref_date);
        $ref_date = date('Y-m-d', strtotime($ref_date));

        // Gets the first day of the last month
        $datestring = $ref_date . ' first day of last month';
        $return_date = date_create($datestring);

        // Return the previous month
        return $return_date->format('m/Y');
    }

    /**
     * Check if a string is a valid date(time)
     *
     * DateTime::createFromFormat requires PHP >= 5.3
     *
     * @param string $str_dt
     * @param string $str_dateformat
     * @param string $str_timezone (If timezone is invalid, php will throw an exception)
     * @return bool
     */
    public static function verifyDate($str_dt, $str_dateformat, $str_timezone)
    {
        $date = DateTime::createFromFormat($str_dateformat, $str_dt, new DateTimeZone($str_timezone));
        return $date && DateTime::getLastErrors()["warning_count"] == 0 && DateTime::getLastErrors()["error_count"] == 0;
    }
}