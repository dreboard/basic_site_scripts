<?php

namespace App\Main;


abstract class Security
{
    private const OPENSSL_SECRET_KEY = 'eDc2QjZwRk1wcXo1R1hjMHZXbjVJQT09';
    private const OPENSSL_SECRET_IV = 'bRuD5WYw5wd0rdHR9yLlM6wt2vteuiniQBqE70nAuhU=';
    private const OPENSSL_METHOD = 'AES-256-CBC';

    /**
     * Encrypt/Decrypt session data
     * @param string $action
     * @param $string
     * @return string
     */
    public static function encrypt_decrypt($action, $string) {
        $output = false;
        $key = hash('sha256', self::OPENSSL_SECRET_KEY);

        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', self::OPENSSL_SECRET_IV), 0, 16);
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, self::OPENSSL_METHOD, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), self::OPENSSL_METHOD, $key, 0, $iv);
        }
        return $output;
    }

}