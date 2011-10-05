<?php
/**
 * TcpStream
 *
 * Klasa do obs�gi tcp jako plik�w
 * pomaga przy sprawdzaniu file_exist etc.
 * na podstawie: http://www.php.net/manual/en/streamwrapper.url-stat.php
 *
 * @category	Mkphp
 * @package	TcpStream
 * @author	bskrzypkowiak
 */
Class TcpStream {

    /**
     * Sprawdzanie protoko�u
     * 
     * @param String $path
     * @param Integer $flags
     * @return Boolean
     */
    public static function url_stat($path, $flags) {

        if (!stream_socket_client($path, $errno, $errstr, 10)) {
            echo "Brak po��czenia: \r\n$path \r\n $errstr";
            return false;
        }

        return true;
    }

    /**
     * Przy pr�bie utworzenia pliku dla "tcp" nic nie tw�rz i zwr�� true
     * 
     * @param String $path
     * @param Integer $mode
     * @param Integer $options
     * @return Boolean 
     */
    public static function mkdir($path, $mode, $options) {
        return true;
    }

}