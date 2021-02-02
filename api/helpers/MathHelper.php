<?php

namespace api\helpers;

//********************************** CONSTANTS *********************************
define("EPSILON", 0.000000001);
//******************************************************************************

/**
 * Class MathHelper
 *
 * This class is responsible for handle math operations
 *
 * @package api\helpers
 */
class MathHelper
{
    //!*****************************************************************************
    // MATH MANIPULATION functions
    //!*****************************************************************************

    /**
     * Compare a float point number with another or an integer
     * @value1 => the float point value to be compared
     * @value1 => could be a float pointer or an integer
     */
    public static function compare_float( $value1, $value2 )
    {
        // Converts currency to decimal point
        $value1 = str_replace(",", ".", $value1);
        $value2 = str_replace(",", ".", $value2);

        if ( abs(($value1 - $value2)) < EPSILON )
            return true;
        else
            return false;
    }

    /**
     * Drop the decimal places without rounding
     * @value1 => the float point value to be changed
     */
    public static function format_number_precision( $value1 )
    {
        $str = number_format($value1, 4, '.', '');
        return preg_replace('/(?<=\d{2})0+$/', '', $str);
    }

    /**
     * Drop the decimal places without rounding
     * @value1 => the float point value to be changed
     */
    public static function format_number_brazillian( $value1 )
    {
        return number_format($value1, 2, ',', '.');
    }

    /**
     * Drop the decimal places without rounding
     * @value1 => the float point value to be changed
     */
    public static function format_number_brazillian_nodecimals( $value1 )
    {
        return number_format($value1, 0, ',', '.');
    }

    /**
     * Convert a string value to decimal
     * @param value => the value to be converted
     *
     * @return float
     */
    public static function string_to_decimal( $value )
    {
        return floatval(str_replace(',', '.', str_replace('.', '', $value)));
    }

    /**
     * Change the string format to generic decimal point
     * @param value => the value to be converted
     *
     * @return string
     */
    public static function string_decimal_point( $value )
    {
        return MathHelper::string_to_decimal(StringHelper::v_num($value));
    }

    //!*****************************************************************************
    // CURRENCY MANIPULATION public static functionS
    //!*****************************************************************************

    /**
     * Execute operations with generic currencies
     * @value1_ => first operation value
     * @value2_ => second operation value
     * @op_ => the operation itself
     */
    public static function currency_operation( $value1_, $value2_, $op_ )
    {
        // Remove the unecessary characters from money
        $value1_ = str_replace (".", "", $value1_);
        $value1_ = str_replace (",", ".", $value1_);

        $value2_ = str_replace (".", "", $value2_);
        $value2_ = str_replace (",", ".", $value2_);

        // Check wich opration will be executes
        if ( $op_ == "+" ) 	//!< SUM
            $result = $value1_ + $value2_;
        else if ( $op_ == "-" ) //!< SUBTRACTION
            $result = $value1_ - $value2_;
        else if ( $op_ == "*" ) //!< MULTIPLICATION
            $result = $value1_ * $value2_;
        else if ( $op_ == "/" ) //!< DIVISION
            $result = $value1_ / $value2_;
        else //!< INVALID
            return 0;

        // Return the result
        return $result;
    }

    /**
     * Convert a currency to Brazilian real standards
     * @param value => the value to be converted
     *
     * @return float
     */
    public static function real_currency( $value )
    {
        return number_format(floatval($value), 2, ',', '.');
    }

    /**
     * Convert a currency to American USD standards
     * @param value => the value to be converted
     *
     * @return float
     */
    public static function usd_currency( $value )
    {
        return number_format(floatval($value), 2, '.', ',');
    }

    /**
     * Sum money in Brazilian real standards
     * @value01 => value 01 to be summed
     * @value02 => value 02 to be summed
     */
    public static function real_sum( $value01, $value02 )
    {
        // Change commas per dots
        $v1 = str_replace(',' , '.', $value01);
        $v2 = str_replace(',' , '.', $value02);

        // Sum the values
        $value = $v1 + $v2;

        // Format in Brazilian real standards
        return $value;
    }

    /**
     * Remove double dots (if it's necessary)
     * @value01 => value to be adjusted
     */
    public static function remove_double_dots( $value_ )
    {
        //! Count the dots
        $value = $value_;
        $dots = substr_count($value, '.');

        if ( $dots > 1 )
            $value = preg_replace('/\./', '', $value, ($dots - 1));

        // Return the value adjusted
        return $value;
    }
}