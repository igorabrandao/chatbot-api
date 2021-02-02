<?php

namespace api\helpers;

/**
 * Class UnitHelper
 *
 * This class is responsible for handle units operations
 *
 * @package api\helpers
 */
class UnitHelper
{
    //!*****************************************************************************
    // UNITS MANIPULATION FUNCTIONS
    //!*****************************************************************************

    /**
     * Format value to XXmb XXkb
     *
     * @value => value to be formatted
     * @return string
     */
    public static function formatMbKb( $value )
    {
        // Format the value
        $total = number_format($value, 3);
        $total = str_replace(".", "mb ", $total . "kb");
        $total = str_replace(",", "", $total);
        return $total;
    }
}