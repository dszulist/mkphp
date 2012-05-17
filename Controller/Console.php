<?php

/**
 * MK_Controller_Console
 *
 * Klasa do obsługi wywoływania aplikacji z lini polecen (CLI)
 *
 * @category	MK_Controller
 * @package     MK_Controller_Console
 * @author	bskrzypkowiak
 */
class MK_Controller_Console {

	/**
	 * Adres remote wbijany dla wywolania metoda CLI
	 * @var string
	 */
	private $remoteAddress = '127.0.0.1';

	/**
	 * Konstruktor
	 */
	public function __construct() {
		$this->_setServerVariables();
	}

	/**
	 *  Ustawiam Remote_addr w przypadku gdy uruchamiam skrypt z konsoli
	 */
	private function _setServerVariables() {
		putenv("REMOTE_ADDR=$this->remoteAddress");
		$_SERVER['HTTP_HOST'] = exec('hostname');
		$_SERVER['SERVER_ADDR'] = 'localhost';
		$_SERVER['REMOTE_ADDR'] = $this->remoteAddress;
		$_SERVER['HTTP_USER_AGENT'] = 'madkom_console';
		$_SERVER['REQUEST_URI'] = 'localhost';
	}

    /**
     * Zwraca najważniejsze informacje dotyczace aplikacji (DLA Admina)
     *
     *     php index.php -mappinfo
     * @param array $argv
     */
	public function appinfo(array $argv) {
		echo "APP=" . strtolower(APP_NAME) . PHP_EOL .
		     "DATABASE=" . DB_NAME . PHP_EOL .
		     "PASS=" . DB_PASS . PHP_EOL .
		     "USER=" . DB_USER . PHP_EOL .
		     "DBHOST=" . DB_HOST . PHP_EOL .
		     "PORT=" . DB_PORT . PHP_EOL;

		$db = new MK_Db_PDO();
		die("VERSION=" . $db->getAppVersion() . PHP_EOL);
	}

    /**
     * Zwraca najważniejsze informacje dotyczace aplikacji (DLA Admina)
     *
     *     php index.php -mapplogs
     * @param array $argv
     */
	public function applogs(array $argv) {
		$debug = (isset($argv[0]) && $argv[0] == 'true');
		$logs = new MK_Logs(APP_PATH, $debug);
		exit($logs->sendPackage() ? 'true' : 'false');
	}

}