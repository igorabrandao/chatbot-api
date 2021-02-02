<?php

namespace api\helpers;

/**
 * Class StringHelper
 *
 * This class is responsible for handle string operations
 *
 * @package api\helpers
 */
class StringHelper
{
    //!*****************************************************************************
    // STRING MANIPULATION FUNCTIONS
    //!*****************************************************************************

    public static function my_sql_regcase($str)
    {
        $res = "";
        $chars = str_split($str);

        foreach ($chars as $char) {
            if (preg_match("/[A-Za-z]/", $char))
                $res .= "[" . mb_strtoupper($char, 'UTF-8') . mb_strtolower($char, 'UTF-8') . "]";
            else
                $res .= $char;
        }

        return $res;
    }

    /**
     * Simulate the function in_array in a multidimensional array
     * @needle        => the info to be searched
     * @haystack    => the array where the info should be contained
     * @strict        => this parameter define if the value should exaclty equal
     */
    public static function in_array_r($needle, $haystack, $strict = true)
    {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Apply generically mask to values
     * @mask => the file to be read
     * @string => the file to be read
     */
    public static function mask_string($mask, $string)
    {
        $string = str_replace(" ", "", $string);
        for ($i = 0; $i < strlen($string); $i++) {
            $mask[strpos($mask, "#")] = $string[$i];
        }
        return $mask;
    }

    /**
     * function to remove all accents variations
     * @str => the string to be changed
     */
    public static function remove_accent($str)
    {
        $unwanted_array = array('Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
            'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y');
        $str = strtr($str, $unwanted_array);
        return $str;
    }

    /**
     * Replace the last occurrence of a string
     * @search => the term to be searched
     * @replace => the term that replaces the search
     * @str => the string itself
     */
    public static function str_replace_last($search, $replace, $str)
    {
        if (($pos = strrpos($str, $search)) !== false) {
            $search_length = strlen($search);
            $str = substr_replace($str, $replace, $pos, $search_length);
        }
        return $str;
    }

    /**
     * Remove all alphanumeric characters in a string with numbers
     * @value => the value to be converted
     */
    public static function v_num($value)
    {
        $value = preg_replace(
            array(
                '/[^\d,]/',    // Matches anything that's not a comma or number.
                '/(?<=,),+/',  // Matches consecutive commas.
                '/^,+/',       // Matches leading commas.
                '/,+$/'        // Matches trailing commas.
            ),
            '',                // Remove all matched substrings.
            $value
        );

        return $value;
    }

    /**
     * Simulate the explode function using an array as a delimiter
     * @delimiters => array with multiple delimiters
     * @str => the string to be exploded
     * @keep_delimiter => true -> keep delimiters, false -> exclude delimiters
     */
    public static function multi_explode($delimiters, $str, $keep_delimiter)
    {
        // Check if it's necessary keep delimiters
        if ($keep_delimiter == false) {
            $ready = str_replace($delimiters, $delimiters[0], $str);
            $result = explode($delimiters[0], $ready);
        } else {
            // Generate a preg with multiple delimiters
            $preg_delimiter = '/(';
            for ($i = 0; $i < sizeof($delimiters); $i++) {
                $preg_delimiter .= $delimiters[$i] . '|';
            }
            $preg_delimiter = substr($preg_delimiter, 0, (strlen($preg_delimiter) - 1)) . ')/';

            // Delimite the string
            $result = preg_split($preg_delimiter, $str, -1, PREG_SPLIT_DELIM_CAPTURE);
        }
        return $result;
    }

    public static function multi_explode_keep_delimiter($str)
    {
        $arr = preg_split("/(Dados GPRS|Navegação Web|Torpedo|Interatividade Globo)/", $str, -1, PREG_SPLIT_DELIM_CAPTURE);
        return $arr;
    }

    /**
     * function to perform an strpos function with an array as a parameter
     *
     * @haystack => block of content
     * @needle     => the array containing the strings to be tested
     */
    public static function strposArray($haystack, $needle, $offset = 0)
    {
        //! Check if the needle is an array
        if (!is_array($needle)) {
            $needle = array($needle);
        }

        //! Run trough all needle itens to test with it exists
        foreach ($needle as $query) {
            if (strpos($haystack, $query, $offset) !== false)
                return true; // stop on first true result
        }

        return false;
    }

    /**
     * function to replace the nth occurrence in a string
     * @search => the term to be replaced
     * @replace => the term that'll replace
     * @subject => true -> the string itself
     * @nth => true -> the occurrence to be replaced
     */
    public static function str_replace_nth($search, $replace, $subject, $nth)
    {
        $found = preg_match_all('/' . preg_quote($search) . '/', $subject, $matches, PREG_OFFSET_CAPTURE);
        if (false !== $found && $found > $nth) {
            return substr_replace($subject, $replace, $matches[0][$nth][1], strlen($search));
        }
        return $subject;
    }

    /**
     * function to reverse the word position in a sentence
     * @str_ => string to be reversed
     */
    public static function reverse_sentence($str_)
    {
        //! Reverse the sentence
        $aux_str = preg_split('/\s+/', rtrim(ltrim($str_)));

        $final_str = "";
        for ($i = sizeof($aux_str) - 1; $i >= 0; $i--)
            $final_str .= trim($aux_str[$i]) . " ";

        return $final_str;
    }

    /**
     * public static function to explode just the last occurence from a string
     * @str_ => string to be exploded
     * @delimiter_ => the pattern to explode the string
     * @regex_ => true: uses preg split / false: uses explode
     */
    public static function explode_last_occurrence($delimiter_, $str_, $regex_)
    {
        //! Auxiliary variables
        $begin = "";
        $end = "";

        //! Split the string
        if ($regex_ == false) {
            //! Without regex
            $explode = explode($delimiter_, $str_);
        } else {
            //! With regex
            $explode = preg_split($delimiter_, $str_);
            preg_match_all($delimiter_, $str_, $match_values);
        }

        if (count($explode) > 0) {
            //! Removes the last element, and returns it
            $end = array_pop($explode);

            //! Glue the remaining pieces back together
            if (count($explode) > 0) {
                if ($regex_ == false)
                    $begin = implode($delimiter_, $explode);
                else {
                    //! Run through regex array result and glue all dynamic delimiters
                    for ($i = 0; $i < sizeof($match_values[0]); $i++) {
                        $begin .= $explode[$i] . " " . $match_values[0][$i];
                    }
                }
            }
        }

        return $begin;
    }

    /**
     * public static function that replaces html entities with ascii near-equivalents
     * @text => the float point value to be compared
     */
    public static function asciify($text)
    {
        $entities = array();
        $ascii = array();

        // 32 through 127 correspond to ascii letters
        for ($i = 32; $i < 127; $i++) {
            $entities[] = "&#$i;";
            $ascii[] = chr($i);
        }

        // 32 through 99 have alternates with padding
        for ($i = 32; $i < 100; $i++) {
            $entities[] = "&#0$i;";
            $ascii[] = chr($i);
        }

        $entities[] = "&#160;";
        $ascii[] = ' '; # non-breaking space
        $entities[] = "&#161;";
        $ascii[] = '!'; # inverted exclamation mark
        $entities[] = "&#162;";
        $ascii[] = 'cents'; # cent sign
        $entities[] = "&#163;";
        $ascii[] = 'pounds'; # pound sign
        $entities[] = "&#164;";
        $ascii[] = '$'; # currency sign
        $entities[] = "&#165;";
        $ascii[] = 'yen'; # yen sign
        $entities[] = "&#166;";
        $ascii[] = '|'; # broken vertical bar
        $entities[] = "&#167;";
        $ascii[] = 'Ss'; # section sign
        $entities[] = "&#168;";
        $ascii[] = '``'; # spacing diaeresis - umlaut
        $entities[] = "&#169;";
        $ascii[] = '(c)'; # copyright sign
        $entities[] = "&#170;";
        $ascii[] = 'a'; # feminine ordinal indicator
        $entities[] = "&#171;";
        $ascii[] = '<<'; # left double angle quotes
        $entities[] = "&#172;";
        $ascii[] = '~'; # not sign
        $entities[] = "&#173;";
        $ascii[] = '-'; # soft hyphen
        $entities[] = "&#174;";
        $ascii[] = '(r)'; # registered trade mark sign
        $entities[] = "&#175;";
        $ascii[] = '-'; # spacing macron - overline
        $entities[] = "&nbsp;";
        $ascii[] = ' '; # non-breaking space
        $entities[] = "&iexcl;";
        $ascii[] = '!'; # inverted exclamation mark
        $entities[] = "&cent;";
        $ascii[] = 'cents'; # cent sign
        $entities[] = "&pound;";
        $ascii[] = 'pounds'; # pound sign
        $entities[] = "&curren;";
        $ascii[] = '$'; # currency sign
        $entities[] = "&yen;";
        $ascii[] = 'yen'; # yen sign
        $entities[] = "&brvbar;";
        $ascii[] = '|'; # broken vertical bar
        $entities[] = "&sect;";
        $ascii[] = 'Ss'; # section sign
        $entities[] = "&uml;";
        $ascii[] = '``'; # spacing diaeresis - umlaut
        $entities[] = "&copy;";
        $ascii[] = '(c)'; # copyright sign
        $entities[] = "&ordf;";
        $ascii[] = 'a'; # feminine ordinal indicator
        $entities[] = "&laquo;";
        $ascii[] = '<<'; # left double angle quotes
        $entities[] = "&not;";
        $ascii[] = '~'; # not sign
        $entities[] = "&shy;";
        $ascii[] = '-'; # soft hyphen
        $entities[] = "&reg;";
        $ascii[] = '(r)'; # registered trade mark sign
        $entities[] = "&macr;";
        $ascii[] = '-'; # spacing macron - overline
        $entities[] = "&#176;";
        $ascii[] = 'deg'; # degree sign
        $entities[] = "&#177;";
        $ascii[] = '+/-'; # plus-or-minus sign
        $entities[] = "&#178;";
        $ascii[] = '^2'; # superscript two - squared
        $entities[] = "&#179;";
        $ascii[] = '^3'; # superscript three - cubed
        $entities[] = "&#180;";
        $ascii[] = '\''; # acute accent - spacing acute
        $entities[] = "&#181;";
        $ascii[] = 'u'; # micro sign
        $entities[] = "&#182;";
        $ascii[] = 'par'; # pilcrow sign - paragraph sign
        $entities[] = "&#183;";
        $ascii[] = '.'; # middle dot - Georgian comma
        $entities[] = "&#184;";
        $ascii[] = ','; # spacing cedilla
        $entities[] = "&#185;";
        $ascii[] = '^1'; # superscript one
        $entities[] = "&#186;";
        $ascii[] = '^o'; # masculine ordinal indicator
        $entities[] = "&#187;";
        $ascii[] = '>>'; # right double angle quotes
        $entities[] = "&#188;";
        $ascii[] = '1/4'; # fraction one quarter
        $entities[] = "&#189;";
        $ascii[] = '1/2'; # fraction one half
        $entities[] = "&#190;";
        $ascii[] = '3/4'; # fraction three quarters
        $entities[] = "&#191;";
        $ascii[] = '?'; # inverted question mark
        $entities[] = "&deg;";
        $ascii[] = 'deg'; # degree sign
        $entities[] = "&plusmn;";
        $ascii[] = '+/-'; # plus-or-minus sign
        $entities[] = "&sup2;";
        $ascii[] = '^2';  # superscript two - squared
        $entities[] = "&sup3;";
        $ascii[] = '^3';  # superscript three - cubed
        $entities[] = "&acute;";
        $ascii[] = '\'';  # acute accent - spacing acute
        $entities[] = "&micro;";
        $ascii[] = 'u'; # micro sign
        $entities[] = "&para;";
        $ascii[] = 'par'; # pilcrow sign - paragraph sign
        $entities[] = "&middot;";
        $ascii[] = '.'; # middle dot - Georgian comma
        $entities[] = "&cedil;";
        $ascii[] = ','; # spacing cedilla
        $entities[] = "&sup1;";
        $ascii[] = '^1';  # superscript one
        $entities[] = "&ordm;";
        $ascii[] = '^o';  # masculine ordinal indicator
        $entities[] = "&raquo;";
        $ascii[] = '>>';  # right double angle quotes
        $entities[] = "&frac14;";
        $ascii[] = '1/4'; # fraction one quarter
        $entities[] = "&frac12;";
        $ascii[] = '1/2'; # fraction one half
        $entities[] = "&frac34;";
        $ascii[] = '3/4'; # fraction three quarters
        $entities[] = "&iquest;";
        $ascii[] = '?'; # inverted question mark
        $entities[] = "&#192;";
        $ascii[] = 'A'; # latin capital letter A with grave
        $entities[] = "&#193;";
        $ascii[] = 'A'; # latin capital letter A with acute
        $entities[] = "&#194;";
        $ascii[] = 'A'; # latin capital letter A with circumflex
        $entities[] = "&#195;";
        $ascii[] = 'A'; # latin capital letter A with tilde
        $entities[] = "&#196;";
        $ascii[] = 'A'; # latin capital letter A with diaeresis
        $entities[] = "&#197;";
        $ascii[] = 'A'; # latin capital letter A with ring above
        $entities[] = "&#198;";
        $ascii[] = 'AE'; # latin capital letter AE
        $entities[] = "&#199;";
        $ascii[] = 'C'; # latin capital letter C with cedilla
        $entities[] = "&#200;";
        $ascii[] = 'E'; # latin capital letter E with grave
        $entities[] = "&#201;";
        $ascii[] = 'E'; # latin capital letter E with acute
        $entities[] = "&#202;";
        $ascii[] = 'E'; # latin capital letter E with circumflex
        $entities[] = "&#203;";
        $ascii[] = 'E'; # latin capital letter E with diaeresis
        $entities[] = "&#204;";
        $ascii[] = 'I'; # latin capital letter I with grave
        $entities[] = "&#205;";
        $ascii[] = 'I'; # latin capital letter I with acute
        $entities[] = "&#206;";
        $ascii[] = 'I'; # latin capital letter I with circumflex
        $entities[] = "&#207;";
        $ascii[] = 'I'; # latin capital letter I with diaeresis
        $entities[] = "&Agrave;";
        $ascii[] = 'A'; # latin capital letter A with grave
        $entities[] = "&Aacute;";
        $ascii[] = 'A'; # latin capital letter A with acute
        $entities[] = "&Acirc;";
        $ascii[] = 'A'; # latin capital letter A with circumflex
        $entities[] = "&Atilde;";
        $ascii[] = 'A'; # latin capital letter A with tilde
        $entities[] = "&Auml;";
        $ascii[] = 'A'; # latin capital letter A with diaeresis
        $entities[] = "&Aring;";
        $ascii[] = 'A'; # latin capital letter A with ring above
        $entities[] = "&AElig;";
        $ascii[] = 'AE'; # latin capital letter AE
        $entities[] = "&Ccedil;";
        $ascii[] = 'C'; # latin capital letter C with cedilla
        $entities[] = "&Egrave;";
        $ascii[] = 'E'; # latin capital letter E with grave
        $entities[] = "&Eacute;";
        $ascii[] = 'E'; # latin capital letter E with acute
        $entities[] = "&Ecirc;";
        $ascii[] = 'E'; # latin capital letter E with circumflex
        $entities[] = "&Euml;";
        $ascii[] = 'E'; # latin capital letter E with diaeresis
        $entities[] = "&Igrave;";
        $ascii[] = 'I'; # latin capital letter I with grave
        $entities[] = "&Iacute;";
        $ascii[] = 'I'; # latin capital letter I with acute
        $entities[] = "&Icirc;";
        $ascii[] = 'I'; # latin capital letter I with circumflex
        $entities[] = "&Iuml;";
        $ascii[] = 'I'; # latin capital letter I with diaeresis
        $entities[] = "&#208;";
        $ascii[] = 'EDH'; # latin capital letter ETH
        $entities[] = "&#209;";
        $ascii[] = 'N'; # latin capital letter N with tilde
        $entities[] = "&#210;";
        $ascii[] = 'O'; # latin capital letter O with grave
        $entities[] = "&#211;";
        $ascii[] = 'O'; # latin capital letter O with acute
        $entities[] = "&#212;";
        $ascii[] = 'O'; # latin capital letter O with circumflex
        $entities[] = "&#213;";
        $ascii[] = 'O'; # latin capital letter O with tilde
        $entities[] = "&#214;";
        $ascii[] = 'O'; # latin capital letter O with diaeresis
        $entities[] = "&#215;";
        $ascii[] = 'x'; # multiplication sign
        $entities[] = "&#216;";
        $ascii[] = '0'; # latin capital letter O with slash
        $entities[] = "&#217;";
        $ascii[] = 'U'; # latin capital letter U with grave
        $entities[] = "&#218;";
        $ascii[] = 'U'; # latin capital letter U with acute
        $entities[] = "&#219;";
        $ascii[] = 'U'; # latin capital letter U with circumflex
        $entities[] = "&#220;";
        $ascii[] = 'U'; # latin capital letter U with diaeresis
        $entities[] = "&#221;";
        $ascii[] = 'Y'; # latin capital letter Y with acute
        $entities[] = "&#222;";
        $ascii[] = 'dh'; # latin capital letter THORN
        $entities[] = "&#223;";
        $ascii[] = 'th'; # latin small letter sharp s - ess-zed
        $entities[] = "&ETH;";
        $ascii[] = 'EDH'; # latin capital letter ETH
        $entities[] = "&Ntilde;";
        $ascii[] = 'N';  # latin capital letter N with tilde
        $entities[] = "&Ograve;";
        $ascii[] = 'O';  # latin capital letter O with grave
        $entities[] = "&Oacute;";
        $ascii[] = 'O';  # latin capital letter O with acute
        $entities[] = "&Ocirc;";
        $ascii[] = 'O';  # latin capital letter O with circumflex
        $entities[] = "&Otilde;";
        $ascii[] = 'O';  # latin capital letter O with tilde
        $entities[] = "&Ouml;";
        $ascii[] = 'O';  # latin capital letter O with diaeresis
        $entities[] = "&times;";
        $ascii[] = 'x';  # multiplication sign
        $entities[] = "&Oslash;";
        $ascii[] = 'O';  # latin capital letter O with slash
        $entities[] = "&Ugrave;";
        $ascii[] = 'U';  # latin capital letter U with grave
        $entities[] = "&Uacute;";
        $ascii[] = 'U';  # latin capital letter U with acute
        $entities[] = "&Ucirc;";
        $ascii[] = 'U';  # latin capital letter U with circumflex
        $entities[] = "&Uuml;";
        $ascii[] = 'U';  # latin capital letter U with diaeresis
        $entities[] = "&Yacute;";
        $ascii[] = 'Y';  # latin capital letter Y with acute
        $entities[] = "&THORN;";
        $ascii[] = 'dh'; # latin capital letter THORN
        $entities[] = "&szlig;";
        $ascii[] = 'th'; # latin small letter sharp s - ess-zed
        $entities[] = "&#224;";
        $ascii[] = 'a'; # latin small letter a with grave
        $entities[] = "&#225;";
        $ascii[] = 'a'; # latin small letter a with acute
        $entities[] = "&#226;";
        $ascii[] = 'a'; # latin small letter a with circumflex
        $entities[] = "&#227;";
        $ascii[] = 'a'; # latin small letter a with tilde
        $entities[] = "&#228;";
        $ascii[] = 'a'; # latin small letter a with diaeresis
        $entities[] = "&#229;";
        $ascii[] = 'a'; # latin small letter a with ring above
        $entities[] = "&#230;";
        $ascii[] = 'ae'; # latin small letter ae
        $entities[] = "&#231;";
        $ascii[] = 'c'; # latin small letter c with cedilla
        $entities[] = "&#232;";
        $ascii[] = 'e'; # latin small letter e with grave
        $entities[] = "&#233;";
        $ascii[] = 'e'; # latin small letter e with acute
        $entities[] = "&#234;";
        $ascii[] = 'e'; # latin small letter e with circumflex
        $entities[] = "&#235;";
        $ascii[] = 'e'; # latin small letter e with diaeresis
        $entities[] = "&#236;";
        $ascii[] = 'i'; # latin small letter i with grave
        $entities[] = "&#237;";
        $ascii[] = 'i'; # latin small letter i with acute
        $entities[] = "&#238;";
        $ascii[] = 'i'; # latin small letter i with circumflex
        $entities[] = "&#239;";
        $ascii[] = 'i'; # latin small letter i with diaeresis
        $entities[] = "&agrave;";
        $ascii[] = 'a';  # latin small letter a with grave
        $entities[] = "&aacute;";
        $ascii[] = 'a';  # latin small letter a with acute
        $entities[] = "&acirc;";
        $ascii[] = 'a';  # latin small letter a with circumflex
        $entities[] = "&atilde;";
        $ascii[] = 'a';  # latin small letter a with tilde
        $entities[] = "&auml;";
        $ascii[] = 'a';  # latin small letter a with diaeresis
        $entities[] = "&aring;";
        $ascii[] = 'a';  # latin small letter a with ring above
        $entities[] = "&aelig;";
        $ascii[] = 'ae'; # latin small letter ae
        $entities[] = "&ccedil;";
        $ascii[] = 'c';  # latin small letter c with cedilla
        $entities[] = "&egrave;";
        $ascii[] = 'e';  # latin small letter e with grave
        $entities[] = "&eacute;";
        $ascii[] = 'e';  # latin small letter e with acute
        $entities[] = "&ecirc;";
        $ascii[] = 'e';  # latin small letter e with circumflex
        $entities[] = "&euml;";
        $ascii[] = 'e';  # latin small letter e with diaeresis
        $entities[] = "&igrave;";
        $ascii[] = 'i';  # latin small letter i with grave
        $entities[] = "&iacute;";
        $ascii[] = 'i';  # latin small letter i with acute
        $entities[] = "&icirc;";
        $ascii[] = 'i';  # latin small letter i with circumflex
        $entities[] = "&iuml;";
        $ascii[] = 'i';  # latin small letter i with diaeresis
        $entities[] = "&#240;";
        $ascii[] = 'edh'; # latin small letter eth
        $entities[] = "&#241;";
        $ascii[] = 'n'; # latin small letter n with tilde
        $entities[] = "&#242;";
        $ascii[] = 'o'; # latin small letter o with grave
        $entities[] = "&#243;";
        $ascii[] = 'o'; # latin small letter o with acute
        $entities[] = "&#244;";
        $ascii[] = 'o'; # latin small letter o with circumflex
        $entities[] = "&#245;";
        $ascii[] = 'o'; # latin small letter o with tilde
        $entities[] = "&#246;";
        $ascii[] = 'o'; # latin small letter o with diaeresis
        $entities[] = "&#247;";
        $ascii[] = '/'; # division sign
        $entities[] = "&#248;";
        $ascii[] = 'o'; # latin small letter o with slash
        $entities[] = "&#249;";
        $ascii[] = 'u'; # latin small letter u with grave
        $entities[] = "&#250;";
        $ascii[] = 'u'; # latin small letter u with acute
        $entities[] = "&#251;";
        $ascii[] = 'u'; # latin small letter u with circumflex
        $entities[] = "&#252;";
        $ascii[] = 'u'; # latin small letter u with diaeresis
        $entities[] = "&#253;";
        $ascii[] = 'y'; # latin small letter y with acute
        $entities[] = "&#254;";
        $ascii[] = 'th'; # latin small letter thorn
        $entities[] = "&#255;";
        $ascii[] = 'y'; # latin small letter y with diaeresis
        $entities[] = "&eth;";
        $ascii[] = 'edh'; # latin small letter eth
        $entities[] = "&ntilde;";
        $ascii[] = 'n';  # latin small letter n with tilde
        $entities[] = "&ograve;";
        $ascii[] = 'o';  # latin small letter o with grave
        $entities[] = "&oacute;";
        $ascii[] = 'o';  # latin small letter o with acute
        $entities[] = "&ocirc;";
        $ascii[] = 'o';  # latin small letter o with circumflex
        $entities[] = "&otilde;";
        $ascii[] = 'o';  # latin small letter o with tilde
        $entities[] = "&ouml;";
        $ascii[] = 'o';  # latin small letter o with diaeresis
        $entities[] = "&divide;";
        $ascii[] = '/';  # division sign
        $entities[] = "&oslash;";
        $ascii[] = 'o';  # latin small letter o with slash
        $entities[] = "&ugrave;";
        $ascii[] = 'u';  # latin small letter u with grave
        $entities[] = "&uacute;";
        $ascii[] = 'u';  # latin small letter u with acute
        $entities[] = "&ucirc;";
        $ascii[] = 'u';  # latin small letter u with circumflex
        $entities[] = "&uuml;";
        $ascii[] = 'u';  # latin small letter u with diaeresis
        $entities[] = "&yacute;";
        $ascii[] = 'y';  # latin small letter y with acute
        $entities[] = "&thorn;";
        $ascii[] = 'th'; # latin small letter thorn
        $entities[] = "&yuml;";
        $ascii[] = 'y';  # latin small letter y with diaeresis
        $entities[] = "&#338;";
        $ascii[] = 'OE'; # latin capital letter OE
        $entities[] = "&#339;";
        $ascii[] = 'oe'; # latin small letter oe
        $entities[] = "&#352;";
        $ascii[] = 'S'; # latin capital letter S with caron
        $entities[] = "&#353;";
        $ascii[] = 's'; # latin small letter s with caron
        $entities[] = "&#376;";
        $ascii[] = 'U'; # latin capital letter Y with diaeresis
        $entities[] = "&#402;";
        $ascii[] = 'f'; # latin small f with hook - public static function

        // Higher Punctuation
        $entities[] = "&#8194;";
        $ascii[] = ' '; # en space
        $entities[] = "&#8195;";
        $ascii[] = ' '; # em space
        $entities[] = "&#8201;";
        $ascii[] = ' '; # thin space
        $entities[] = "&#8204;";
        $ascii[] = ''; # zero width non-joiner,
        $entities[] = "&#8205;";
        $ascii[] = ''; # zero width joiner
        $entities[] = "&#8206;";
        $ascii[] = ''; # left-to-right mark
        $entities[] = "&#8207;";
        $ascii[] = ''; # right-to-left mark
        $entities[] = "&#8211;";
        $ascii[] = '-'; # en dash
        $entities[] = "&#8212;";
        $ascii[] = '--'; # em dash
        $entities[] = "&#8216;";
        $ascii[] = '\''; # left single quotation mark,
        $entities[] = "&#8217;";
        $ascii[] = '\''; # right single quotation mark,
        $entities[] = "&#8218;";
        $ascii[] = '"'; # single low-9 quotation mark
        $entities[] = "&#8220;";
        $ascii[] = '"'; # left double quotation mark,
        $entities[] = "&#8221;";
        $ascii[] = '"'; # right double quotation mark,
        $entities[] = "&#8222;";
        $ascii[] = ',,'; # double low-9 quotation mark
        $entities[] = "&#8224;";
        $ascii[] = '*'; # dagger
        $entities[] = "&#8225;";
        $ascii[] = '**'; # double dagger
        $entities[] = "&#8226;";
        $ascii[] = '*'; # bullet
        $entities[] = "&#8230;";
        $ascii[] = '...'; # horizontal ellipsis
        $entities[] = "&#8240;";
        $ascii[] = '0/00'; # per mille sign
        $entities[] = "&#8249;";
        $ascii[] = '<'; # single left-pointing angle quotation mark,
        $entities[] = "&#8250;";
        $ascii[] = '>'; # single right-pointing angle quotation mark,
        $entities[] = "&#8364;";
        $ascii[] = 'euro'; # euro sign
        $entities[] = "&euro;";
        $ascii[] = 'euro'; # euro sign
        $entities[] = "&#8482;";
        $ascii[] = '(TM)'; # trade mark sign

        $entities[] = "&amp;";
        $ascii[] = '&'; # ampersand

        $output = str_replace($entities, $ascii, $text);

        // For CDATA: Remove any instances of ]]> that may have accidentally been created.
        // $output = str_replace(']]>', '', $output);

        return $output;
    }

    /**
     * Function to determine if a string contains number of not
     * Uses regular expression to perform the operation
     *
     * @param string_ => the string to be evaluated
     *
     * @return bool
     */
    public static function string_has_number($string_)
    {
        return preg_match('/\\d/', $string_) > 0;
    }

    //!*****************************************************************************
    // STRING COMPARISON FUNCTIONS
    //!*****************************************************************************

    /**
     * Compare a the similarity between strings
     * @param str_a => first string to compare
     * @param str_b => second string to compare
     *
     * @return float => string similarity score
     */
    public static function string_compare($str_a, $str_b)
    {
        $length = strlen($str_a);
        $length_b = strlen($str_b);

        $i = 0;
        $segmentcount = 0;
        $segmentsinfo = array();
        $segment = '';

        while ($i < $length)
        {
            $char = substr($str_a, $i, 1);
            if (strpos($str_b, $char) !== FALSE)
            {
                $segment = $segment.$char;
                if (strpos($str_b, $segment) !== FALSE)
                {
                    $segmentpos_a = $i - strlen($segment) + 1;
                    $segmentpos_b = strpos($str_b, $segment);
                    $positiondiff = abs($segmentpos_a - $segmentpos_b);
                    $posfactor = ($length - $positiondiff) / $length_b; // <-- ?
                    $lengthfactor = strlen($segment)/$length;
                    $segmentsinfo[$segmentcount] = array( 'segment' => $segment, 'score' => ($posfactor * $lengthfactor));
                }
                else
                {
                    $segment = '';
                    $i--;
                    $segmentcount++;
                }
            }
            else
            {
                $segment = '';
                $segmentcount++;
            }
            $i++;
        }

        // PHP 5.3 lambda in array_map
        $totalscore = array_sum(array_map(function($v) { return $v['score'];  }, $segmentsinfo));
        return $totalscore;
    }

    /**
     * Method to perform a diff between 2 json objects
     *
     * @param $json1_
     * @param $json2_
     *
     * @return array
     */
    public function diffJson($json1_, $json2_)
    {
        // First of all decode the json objects
        $json1 = json_decode($json1_, true);
        $json2 = json_decode($json2_, true);

        // Find the duplicates
        $duplicates = array_intersect($json1, $json2);

        // Get the difference (without the duplicates):

        //to remove from $json1
        $json1 = array_diff($json1, $duplicates);

        //to remove from $json2
        $json2 = array_diff($json2, $duplicates);

        // To just remove from $json2 it's much easier:
        $json2 = array_diff($json2, $json1);

        // Return the difference between the json objects
        return $json2;
    }

    /**
     * Method to perform a diff between 2 strings
     *
     * @param $str1_
     * @param $str2_
     * @param $minimum_similarity_score
     *
     * @return bool
     */
    public function diffString($str1_, $str2_, $minimum_similarity_score)
    {
        // Compare the strings and get the similarity score
        $similarity_score = StringHelper::string_compare($str1_, $str2_);

        // Analyse the similarity score
        if ($similarity_score >= $minimum_similarity_score) {
            // The strings can be considered equivalent
            return true;
        } else {
            // The strings cannot be considered equivalent
            return false;
        }
    }
}