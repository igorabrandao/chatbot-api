<?php

namespace api\helpers;

/**
 * Class GenericHelper
 *
 * This class is responsible for handle string operations
 *
 * @package api\helpers
 */
class GenericHelper
{
    //!*****************************************************************************
    // GENERIC FUNCTIONS
    //!*****************************************************************************

    /**
     * Function to validate values
     * @param value => the value to be tested
     *
     * @return value|false
     */
    public static function iif($value)
    {
        // Check if the key exists inside the array
        if (isset($value) && strcmp($value, "") != 0) {
            // Return the value
            return $value;
        }

        // Return false by default
        return false;
    } // iif
}