<?php
namespace Networkteam\Import;

/***************************************************************
 *  (c) 2014 networkteam GmbH - all rights reserved
 ***************************************************************/

class ImportResult {

	/**
	 * @var array
	 */
	protected $errors = array();

	/**
	 * @var integer
	 */
	protected $countImported = 0;

	/**
	 * @var integer
	 */
	protected $countUpdated = 0;

	/**
	 * @var integer
	 */
	protected $countDeleted = 0;

	public function addError($error) {
		$this->errors[] = $error;
	}

	/**
	 * @param integer $count
	 */
	public function incCountImported($count = 1) {
		$this->countImported += $count;
	}

	/**
	 * @param integer $count
	 */
	public function incCountUpdated($count = 1) {
		$this->countUpdated += $count;
	}

	/**
	 * @param integer $count
	 */
	public function incCountDeleted($count = 1) {
		$this->countDeleted += $count;
	}

	/**
	 * @return int
	 */
	public function getCountDeleted() {
		return $this->countDeleted;
	}

	/**
	 * @return int
	 */
	public function getCountImported() {
		return $this->countImported;
	}

	/**
	 * @return int
	 */
	public function getCountUpdated() {
		return $this->countUpdated;
	}

	/**
	 * @return array
	 */
	public function getErrors() {
		return $this->errors;
	}
}