<?php

namespace api\helpers;

/**
 * Class SecurityHelper
 *
 * This class is responsible for handle security operations
 *
 * @package api\helpers
 */
class SecurityHelper
{
    //!*****************************************************************************
    // INFORMATION SECURITY MANAGEMENT FUNCTIONS
    //!*****************************************************************************

    /**
     * public static function generate a hash pattern of encryption
     * @password => the password to be encrypted.
     */
    public static function hashSSHA( $password )
    {
        $key = sha1(rand());
        $key = substr($key, 0, 10);
        $encrypted = base64_encode(sha1($password . $key, true) . $key);
        $hash = array("salt" => $key, "encrypted" => $encrypted);

        // Return the encryption
        return $hash;
    }

    /**
     * Decrypting password
     * @param salt, password
     *
     * @return hash string
     */
    public static function checkhashSSHA( $salt, $password )
    {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }

    /**
     * public static function generate an encrypted URI
     * @value_ => the password to be encrypted
     * @path_ => the final URI
     */
    public static function encrypted_url( $value_, $path_ )
    {
        // Define variable related to encrypted content
        $hash = hashSSHA($value_);	// HASH
        $encrypted_id = $hash["encrypted"]; // Encrypted ID
        $key = $hash["salt"]; 				// KEY
        $qtd_char = strlen($value_);		// LENGTH

        //**********************************************
        // Generate a random array without repetitions
        $posics = range(0, 9);
        shuffle( $posics );

        for ( $z = 0; $z <= $qtd_char; $z++ )
            $randomico[$z] = array_shift( $posics );
        //**********************************************

        // Update the encrypted string
        for ( $z = 0; $z < $qtd_char; $z++ )
            $encrypted_id{$randomico[$z]} = (string)$value_[$z];

        // PATH's value
        $path = $path_ . $encrypted_id . "//" . $key . "**" . $qtd_char;

        // Add positions
        for ( $z = 0; $z < $qtd_char; $z++ )
            $path = $path . $randomico[$z];

        // public static function's return
        return $path;
    }

    /**
     * public static function to decrypt an encrypted URI
     * @param_ => the URI to be decrypted
     * @pattern_ => the pattern used to encrypt
     */
    public static function decrypted_url( $param_, $pattern_ )
    {
        try
        {
            // Define the URI length
            $url = explode($pattern_, $param_);
            $qtd_char = substr($url[1], 0, 1);
            $retorno = "";

            // Get the necessary information
            for ( $i = 1; $i <= $qtd_char; $i++ )
            {
                $posic = substr($url[1], $i, 1);
                $retorno = $retorno . substr($url[0], $posic, 1);
            }

            // Return the clean value
            return $retorno;
        }
        catch ( Exception $e )
        {
            return "";
        }
    }

    /**
     * simple method to encrypt or decrypt a plain text string
     * initialization vector(IV) has to be the same when encrypting and decrypting
     * PHP 5.4.9
     *
     * this is a beginners template for simple encryption decryption
     * before using this in production environments, please read about encryption
     *
     * @param string $action: can be 'encrypt' or 'decrypt'
     * @param string $string: string to encrypt or decrypt
     *
     * @return string
     */
    public static function encrypt_decrypt($action, $string)
    {
        $output = false;

        $encrypt_method = "AES-256-CBC";
        $secret_key = 'This is my secret key';
        $secret_iv = 'This is my secret iv';

        // hash
        $key = hash('sha256', $secret_key);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        }
        else if( $action == 'decrypt' ){
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

        return $output;
    }

    // Generates a strong password of N length containing at least one lower case letter,
    // one uppercase letter, one digit, and one special character. The remaining characters
    // in the password are chosen at random from those four sets.
    //
    // The available characters in each set are user friendly - there are no ambiguous
    // characters such as i, l, 1, o, 0, etc. This, coupled with the $add_dashes option,
    // makes it much easier for users to manually type or speak their passwords.
    //
    // Note: the $add_dashes option will increase the length of the password by
    // floor(sqrt(N)) characters.
    public static function generateStrongPassword( $length = 9, $add_dashes = false, $available_sets = 'luds' )
    {
        $sets = array();
        if(strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if(strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if(strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
        if(strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&*?';
        $all = '';
        $password = '';
        foreach($sets as $set)
        {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];
        $password = str_shuffle($password);
        if(!$add_dashes)
            return $password;
        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while(strlen($password) > $dash_len)
        {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }

    /**
     * public static function to handle malicious input
     * @sql => sql to be used
     */
    public static function anti_injection( $sql )
    {
        // remove palavras que contenham sintaxe sql
        $sql = preg_replace(my_sql_regcase("/(from|select|insert|delete|where|drop table|show tables|#|\*|--|\\\\)/"),"",$sql);
        $sql = trim($sql);//limpa espaços vazio
        $sql = strip_tags($sql);//tira tags html e php
        $sql = addslashes($sql);//Adiciona barras invertidas a uma string
        return $sql;
    }

    /**
     * public static function to start the user session
     * @id_user => user ID
     * @user_info => user information
     * @session_name => session name
     */
    public static function start_session($id_user, $user_info, $session_name)
    {
        // Iniciando a sessão
        session_start();

        // Verificando se a sessão já existe
        if(!isset($_SESSION["$session_name"]))
        {
            $_SESSION["$session_name"] = $user_info;
        }
    }

    /**
     * public static function to destroy the user session
     * @id_user => user ID
     * @session_name => session name
     */
    public static function destroy_session($id_user, $session_name)
    {
        // Iniciando a sessão
        session_start();

        // Verificando se a sessão já existe
        if(isset($_SESSION[$session_name]))
        {
            unset($_SESSION[$session_name]);
        }
    }

    /**
     * Function to create a user cookie
     * @id_user => user ID
     * @cookie_name => cookie name
     */
    public static function create_cookie( $id_user, $cookie_name )
    {
        // Verificando se a sessão já existe
        if ( isset($_COOKIE["$cookie_name"]) )
        {
            setcookie($cookie_name, "", time()-10, "/");
        }

        setcookie($cookie_name, $id_user, 0, '/');
    }

    /**
     * Function to destroy a user cookie
     * @cookie_name => session name
     */
    public static function destroy_cookie( $cookie_name )
    {
        // Verificando se a sessão já existe
        if ( isset($_COOKIE[$cookie_name]) )
        {
            setcookie($cookie_name, "", time()-10, "/");
        }
    }
}