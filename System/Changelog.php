<?php

/**
 * MK_System_Changelog
 *
 * Model dla tabeli system_changelog
 *
 * @category	MK_System
 * @package		MK_System_Changelog
 *
 * @throws		MK_Db_Exception
 */
class MK_System_Changelog extends MK_Db_PDO {

	/**
	 * @var string
	 */
	protected $tableName = 'system_changelog';

	/**
	 * Odczytywanie wszystkich rekordów z tabeli
	 *
	 * @return array
	 */
	public function getList() {
		$sql = 'SELECT * FROM ' . $this->tableName
			. ' ORDER BY id DESC';
		return $this->GetRows($sql);
	}

}